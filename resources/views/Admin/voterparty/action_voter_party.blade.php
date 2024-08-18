<a href="{{ route('parties.edit', [$voter_party->id]) }}" style="color: green !important;"><i class="fa-solid fa-pencil"></i></a>
<a onclick="delete_party({{ $voter_party->id }})" style="color: red !important;"><i class="fa-solid fa-trash"></i></a>
