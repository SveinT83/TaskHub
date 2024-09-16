<h2>Upload New Module</h2>
<form action="{{ route('modules.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="module">Module ZIP File</label>
        <input type="file" name="module" id="module" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>
