@extends('layouts.admin')

@section('content')
    
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 flex-grow-1">Dashboard</h1>
        <button type="button" class="btn btn-success btn-icon-split m-1" name="create_record" id="create_record">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Data</span>
        </button>
    </div>
    
    <!-- Modal Add Siswa-->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalLongTitle" class="modal-title">Modal title</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form method="POST" id="sample_form">
                    <div class="modal-body">
                        <span id="form_result"></span>
                        @csrf
                        <div class="form-group">
                            <label for="formNis">Nis</label>
                            <input type="text" class="form-control mb-3" name="nis" id="nis" value="">
                        </div>
                        <div class="form-group">
                            <label for="formNama">Nama</label>
                            <input type="text" class="form-control mb-3" name="nama" id="nama" value="">
                        </div>
                        <div class="form-group">
                            <label for="formKelas">Kelas</label>
                            <input type="text" class="form-control mb-3" name="kelas" id="kelas" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="h3 mb-2 text-gray-800" id="exampleModalLongTitle">Delete Data</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <div class="modal-body">
                    <h4 style="margin:0; center">Are you sure you want to remove this data?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div> 


    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"> 
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

@endsection

@push('afterstyle')
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css"/>

@endpush

@push('afterscript')

{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>


<script>
var test = "test";

$(document).ready(function()
{
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{route('database.index')}}",
        },
        columns: [
            {data: 'nis', name: 'nis'},
            {data: 'nama', name: 'nama'},
            {data: 'kelas', name: 'kelas'},
            {data: 'action', name:'action', orderable:false},
        ]
    });

    $('#create_record').click(function(){
        $('#sample_form')[0].reset();
        $('#form_result').html("");
        $('#alertSuccess').remove();
        $('.modal-title').text("Tambah Data Baru");
            $('#nis').removeAttr("readonly");
            $('#action_button').val("Add");
            // $('#action').val("Add");
            $('#formModal').modal('show');
            // console.log(test);
    });

    $('#dataTable').on('click','.edit', function(){   
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url:"database/"+id+"/editData",
            dataType:"json",
            success:function(html){
                $('#nis').val(html.data.nis).attr("readonly","readonly");
                $('#nama').val(html.data.nama);
                $('#kelas').val(html.data.kelas);
                $('#hidden_id').val(html.data.id);
                $('.modal-title').text("Edit Data Siswa");
                $('#action_button').val("Edit");
                $('#formModal').modal('show');
            }
        })
    });

    $('#sample_form').on('submit', function(event){
            event.preventDefault();
            if($('#action_button').val() == 'Add')
            {
            $.ajax({
              url:"{{route('database.store')}}",
              method:"POST",
              data: new FormData(this),
              contentType: false,
              cache:false,
              processData: false,
              dataType:"json",
              success:function(data)
              {
              var html = '';
              if(data.errors)
              {
                html = '<div class="alert alert-danger">';
                testX1 = data.errors.length;
                testX2 = Array.isArray(data.errors);
                console.log(testX2);
                if(testX2 == true){
                    for(var count = 0; count < data.errors.length; count++)
                    {
                    html += '<p>' + data.errors[count] + '</p>';
                    }
                } else
                {
                    html += '<p>' + data.errors + '</p>';
                }
                html += '</div>';
              }
              if(data.success)
              {
                html = '<div id="alertSuccess" class="alert alert-success" >' + data.success + '</div>';
                $('#sample_form')[0].reset();
                $('#dataTable').DataTable().ajax.reload();
                setTimeout(function(){
                  $('#formModal').modal('hide');
                  $('#alertSuccess').remove();
                }, 2000);
              }
              $('#form_result').html(html);
              }
            })
        }
        if($('#action_button').val() == 'Edit')
            {
            $.ajax({
              url:"{{route('database.updateDataSiswa')}}",
              method:"POST",
              data: new FormData(this),
              contentType: false,
              cache:false,
              processData: false,
              dataType:"json",
              success:function(data)
              {
              var html = '';
              if(data.errors)
              {
                html = '<div class="alert alert-danger">';
                testX1 = data.errors.length;
                testX2 = Array.isArray(data.errors);
                console.log(testX2);
                if(testX2 == true){
                    for(var count = 0; count < data.errors.length; count++)
                    {
                    html += '<p>' + data.errors[count] + '</p>';
                    }
                } else
                {
                    html += '<p>' + data.errors + '</p>';
                }
                html += '</div>';
              }
              if(data.success)
              {
                html = '<div id="alertSuccess" class="alert alert-success" >' + data.success + '</div>';
                $('#sample_form')[0].reset();
                $('#dataTable').DataTable().ajax.reload();
                setTimeout(function(){
                  $('#formModal').modal('hide');
                  $('#alertSuccess').remove();
                }, 2000);
              }
              $('#form_result').html(html);
              }
            })
        }
    });

    // var user_id;

    $(document).on('click', '.delete', function(){
        user_id = $(this).attr('id');
        console.log(user_id)
        $('#confirmModal').modal('show');
    });

    $('#ok_button').click(function(){
        // user_id = $(this).attr('id');
        $.ajax({
            url:"database/deletedatasiswa/"+user_id,
            beforeSend:function(){
                $('#ok_button').text('Deleting...');
            },
            success:function(data){
                $('#dataTable').DataTable().ajax.reload();
                setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    $('ok_button').text('Delete');
                }, 1000);
            }
        })
    })

});
</script>

@endpush