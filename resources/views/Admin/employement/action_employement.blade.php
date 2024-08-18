<a href="{{ route('employement.edit', [$employement->id]) }}" style="color: green !important;"><i class="fa-solid fa-pencil"></i></a>
<a onclick="delete_employement({{ $employement->id }})" style="color: red !important;"><i class="fa-solid fa-trash"></i></a>
