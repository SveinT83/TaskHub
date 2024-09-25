<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::all(); // Hent alle oppgaver fra databasen
        return view('tasks.index', compact('tasks')); // Returner en view-fil som viser oppgavene
    }

    // Viser skjema for å opprette en oppgave
    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // Validering av input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        // Opprett oppgave i databasen
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'user_id' => Auth::id(),
        ]);

        // Synkroniser med Nextcloud
        try {
            $this->syncWithNextcloud($task);
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Oppgaven ble opprettet, men synkronisering med Nextcloud feilet.');
        }

        return redirect()->route('tasks.index')->with('success', 'Oppgave opprettet og synkronisert med Nextcloud!');
    }

    // Hent og oppdater en oppgave fra Nextcloud
    public function updateFromNextcloud(Task $task)
    {
        $client = new Client();
        $response = $client->get(config('services.nextcloud.base_url') . '/remote.php/dav/tasks/' . $task->nextcloud_task_id, [
            'auth' => [config('services.nextcloud.username'), config('services.nextcloud.password')],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->due_date = isset($data['due']) ? new \DateTime($data['due']) : null;
        $task->save();

        return redirect()->back()->with('success', 'Oppgave oppdatert fra Nextcloud!');
    }

    // Funksjon for å synkronisere med Nextcloud
    protected function syncWithNextcloud($task)
    {
        try {
            // Generer en unik identifikator for kalenderhendelsen
            $uniqueId = uniqid() . '.ics';
            $nextcloudUrl = 'https://cloud.tronderdata.no/remote.php/dav/calendars/' . Auth::user()->email . '/personal/' . $uniqueId;

            // Kalenderhendelse i iCalendar-format
            $body = <<<EOT
                    BEGIN:VCALENDAR
                    VERSION:2.0
                    PRODID:-//TaskHub//NONSGML v1.0//EN
                    BEGIN:VEVENT
                    UID:$uniqueId
                    SUMMARY:{$task->title}
                    DESCRIPTION:{$task->description}
                    DTSTART:{$task->due_date->format('Ymd\THis\Z')}
                    DTEND:{$task->due_date->format('Ymd\THis\Z')}
                    END:VEVENT
                    END:VCALENDAR
                    EOT;

            // Logger forespørselen
            \Log::info('Nextcloud Request', [
                'url' => $nextcloudUrl,
                'headers' => [
                    'Authorization' => 'Bearer ' . Auth::user()->nextcloud_token,
                    'Content-Type' => 'text/calendar',
                ],
                'body' => $body,
            ]);

            // Oppretter klienten for HTTP-forespørsel
            $client = new \GuzzleHttp\Client();

            // Send PUT forespørsel til Nextcloud
            $response = $client->put($nextcloudUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . Auth::user()->nextcloud_token,
                    'Content-Type' => 'text/calendar',
                ],
                'body' => $body,
            ]);

            // Logger responsen fra Nextcloud
            \Log::info('Nextcloud Response', [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ]);

            // Sjekk om hendelsen ble vellykket opprettet (201 Created)
            if ($response->getStatusCode() !== 201) {
                throw new \Exception('Feil under synkronisering med Nextcloud kalender');
            }
        } catch (\Exception $e) {
            \Log::error('Nextcloud Sync Error', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

}
