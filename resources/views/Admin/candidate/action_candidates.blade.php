<a href="{{ route('candidate.edit', [$candidate->id]) }}" style="color: green !important;"><i class="fa-solid fa-pencil"></i></a>
<a onclick="delete_candidate({{ $candidate->id }})" style="color: red !important;"><i class="fa-solid fa-trash"></i></a>
