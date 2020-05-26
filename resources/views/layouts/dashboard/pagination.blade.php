{{-- @if ($pagination->count()>0)
	@php
		$distancia = 2;
		$pag_init = $pagination->currentPage()-$distancia;
		if ($pag_init<1) $pag_init =  1;
		$pag_end = $pagination->currentPage() + 2 + ($pagination->currentPage()-$pag_init);
		if ($pag_end>$pagination->lastPage()) $pag_end = $pagination->lastPage();
	@endphp
	<nav aria-label="Page navigation">
		<ul class="pagination justify-content-center">
			@for ($i = $pag_init; $i <= $pag_end; $i++)
				@php
					$is_select_page = $i == $pagination->currentPage();
				@endphp
				@if ($i==$pag_init && $pagination->currentPage()>1)
					<li class="page-item">
						<a class="page-link" href="{{$pagination->previousPageUrl()}}" tabindex="-1">Anterior</a>
					</li>
				@endif
				<li class="page-item{{$is_select_page?" active":""}}">
					<a class="page-link" href="#">
						{{$i}}
						@if ($is_select_page)
						<span class="sr-only">(current)</span>
						@endif
					</a>
				</li>
				@if ($i==$pag_end && $pagination->currentPage()<$pagination->lastPage())
					<li class="page-item">
						<a class="page-link" href="{{$pagination->nextPageUrl()}}">Siguiente</a>
					</li>
				@endif
			@endfor
		</ul>
	</nav>
@endif --}}
<div class="pagination-cnt">
	{{ $pagination->links() }}
</div>