<nav aria-label="Пример навигации по страницам">
	<ul class="pagination justify-content-center">
		<li class="page-item">
			<a class="page-link" href="javascript:paginator('#patientSearchFilter', <?=$FIRST_PAGE;?>)">1</a>
		</li>
		<li class="page-item <?=$prevPageDisabled;?>">
			<a class="page-link" href="javascript:paginator('#patientSearchFilter', <?=$PREV_PAGE;?>)"><<</a>
		</li>
        <li class="page-item disabled">
            <a class="page-link" href="javascript:paginator('#patientSearchFilter', <?=$PAGE;?>)"><?=$PAGE;?></a>
        </li>
		<li class="page-item <?=$nextPageDisabled;?>">
			<a class="page-link" href="javascript:paginator('#patientSearchFilter', <?=$NEXT_PAGE;?>)">>></a>
		</li>
		<li class="page-item">
			<a class="page-link" href="javascript:paginator('#patientSearchFilter', <?=$LAST_PAGE;?>)"><?=$LAST_PAGE;?></a>
		</li>
	</ul>
</nav>