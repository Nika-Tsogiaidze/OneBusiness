<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="filters" style="margin-bottom: 7px;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control approved-filter">
                           <option value="any">All Requests</option>
                           <option value="0">For Approval</option>
                           <option value="1">Approved</option>
                   </select>
                   <select style="width: 170px; display: inline;" class="form-control uploaded-filter">
                           <option value="any">Any Upload Status</option>
                           <option value="0">Not Uploaded Yet</option>
                           <option value="1">Uploaded</option>
                   </select>
           </div>
            <table id="employeeRequestsDatatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>From Branch</th>
                        <th>Last Duty</th>
                        <th>To Branch</th>
                        <th>Start Duty</th>
                        <th>Type</th>
                        <th>Approved</th>
                        <th>Uploaded</th>
                        <th>Sex</th>
                        <th>Birthdate</th>
                        <th>SSS</th>
                        <th>PHIC</th>
                        <th>HDMF</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
            </table>

        </div>
      </div>
</section>
<script>
let employeeRequestsDatatable = $('#employeeRequestsDatatable').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
                url: "{{ url('getEmployeeRequests') }}",
                data: function (d) {
                        d.approved = $(".approved-filter").val();
                        d.uploaded = $(".uploaded-filter").val();
                        d.corpId = {{ $corpId }};
                }
        },
        columns: [
                {data: 'username', name: 'username'},
                {data: 'from_branch', name: 'from_branch'},
                {data: 'date_end', name: 'date_end'},
                {data: 'to_branch', name: 'to_branch'},
                {data: 'date_start', name: 'date_start'},
                {data: 'type', name: 'type'},
                {data: 'approved', name: 'approved'},
                {data: 'executed', name: 'executed'},
                {data: 'sex', name: 'sex'},
                {data: 'bday', name: 'bday'},
                {data: 'SSS', name: 'SSS'},
                {data: 'PHIC', name: 'PHIC'},
                {data: 'pagibig', name: 'pagibig'},
                {data: 'action', name: 'action', sortable: false, searchable: false}
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                .on('change', function () {
                    column.search($(this).val(), false, false, true).draw();
                });
            });
        }
});
$('.approved-filter, .uploaded-filter').on('change', function () {
        employeeRequestsDatatable.draw();
});

function sendApproveRequest(requestId){
    $.ajax({
        method: "POST", 
        url : "{{ url('approveEmployeeRequest') }}",
        data : {"_token" : "{{ csrf_token() }}", "employeeRequestId" : requestId, corpId :  {{ $corpId }}}
    }).done(function (response){
        if(response == "true") { 
            location.reload();
            // setTimeout(function(){
            //     showAlertModal("Success", "The employee request was approved!");
            // }, 500); 
        }
        else { showAlertModal("Error", "Something went wrong, please contact administration") }
    });
}

function sendDeleteRequest(requestId, element){
    $.ajax({
        method: "POST", 
        url : "{{ url('deleteEmployeeRequest') }}",
        data : {"_token" : "{{ csrf_token() }}", "employeeRequestId" : requestId, corpId :  {{ $corpId }}}
    }).done(function (response){
        if(response == "true") { 
            location.reload();
            // setTimeout(function(){ 
            //     showAlertModal("Success", "The employee request was deleted!"); 
            //     $(element).closest("tr").remove();
            // }, 500); 
        }
        else { showAlertModal("Error", "Something Went Wrong, Please Contact Administration") }
    });
}

function approveRequest(requestId){
    showConfirmModal("Request Confirmation", "Are you sure you want to approve this request?", function(result){ 
        if( result == true ) { sendApproveRequest(requestId)} });
} 

function deleteRequest(requestId, element){
    showConfirmModal("Request Confirmation", "Are you sure you want to delete this request?", function(result){ 
        if( result == true ) { sendDeleteRequest(requestId, element)} });
}
</script>
