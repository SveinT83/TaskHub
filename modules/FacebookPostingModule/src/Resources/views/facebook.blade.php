<?php

<form method="POST" action="{{ route('facebook.post') }}">
    @csrf
    <label for="group_id">Select Group:</label>
    <select name="group_id" id="group_id">
        @foreach ($groups['data'] as $group)
            <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
        @endforeach
    </select>

    <textarea name="message" placeholder="Enter your message"></textarea>
    <button type="submit">Post</button>
</form>
