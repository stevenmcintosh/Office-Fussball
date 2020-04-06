$(document).ready(function() {
	$('.dataTable').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "dom": '<f<t>>'
    } );
    
    $('.homeFixtureTable').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     true,
        "dom": '<f<t>>'
    } );
    
    
    
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});