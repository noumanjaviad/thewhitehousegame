<a href="{{ route('previous_election.edit', [$previous_election->id]) }}" style="color: green !important;"><i class="fa-solid fa-pencil"></i></a>
<a onclick="delete_previous_election({{ $previous_election->id }})" style="color: red !important;"><i class="fa-solid fa-trash"></i></a>
