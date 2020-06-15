@extends('layouts.admin')

@section('content')
    
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- <button type="button" name="edit" id="'.$data->nis_student.'" data-toggle="tooltip" title="Edit Data" class="edit btn btn-warning btn-sm my-1 d-inline"><i class="fa fa-pencil-alt"></i></button> --}}
    </div>

        <!-- Modal Edit Siswa-->
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
                                <label id="nama" for="labelNama">test</label>
                            </div>
                            <div class="form-group">
                                <label for="formMatematika">Matematika</label>
                                <input type="text" class="form-control mb-3" name="matematika" id="matematika" value="">
                            </div>
                            <div class="form-group">
                                <label for="formFisika">Fisika</label>
                                <input type="text" class="form-control mb-3" name="fisika" id="fisika" value="">
                            </div>
                            <div class="form-group">
                                <label for="formKimia">Kimia</label>
                                <input type="text" class="form-control mb-3" name="kimia" id="kimia" value="">
                            </div>
                            <div class="form-group">
                                <label for="formBiologi">Biologi</label>
                                <input type="text" class="form-control mb-3" name="biologi" id="biologi" value="">
                            </div>
                            <div class="form-group">
                                <label for="formSejarah">Sejarah</label>
                                <input type="text" class="form-control mb-3" name="sejarah" id="sejarah" value="">
                            </div>
                            <div class="form-group">
                                <label for="formGeografi">Geografi</label>
                                <input type="text" class="form-control mb-3" name="geografi" id="geografi" value="">
                            </div>
                            <div class="form-group">
                                <label for="formGeografi">Keterangan</label>
                                <input type="text" class="form-control mb-3" name="keteragan" id="keteragan" value="">
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

    <div style="display: auto; overflow-x: auto">
        <table id="dataTable" class="table table-bordered"  cellspacing="0">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Total Nilai</th>
                    <th>Nilai Akhir</th>
                    <th>Matematika</th>
                    <th>Fisika</th>
                    <th>Kimia</th>
                    <th>Biologi</th>
                    <th>Sejarah</th>
                    <th>Geografi</th>
                    <th>Keteragan</th>
                    <th id="actionColumn">Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

@endsection

@push('afterstyle')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css"/>

<style>
    /* table{ table-layout: fixed; width: 100%; } */
/* td { border: 1px solid black; } */
/* td:first-child{ width: 100px; } */
/* td:last-child{ width: 20px; } */
</style>

@endpush

@push('afterscript')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>


<script>
$(document).ready(function()
{    
    $.ajaxSetup({
        headers: {
            // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: "{{route('nilai-detail')}}",
        },
        columns: [
            {data: 'nis_student', name: 'nis_student', className: 'align-middle' },
            {data: 'nama', name: 'nama', className: 'align-middle' },
            {data: 'totalNilai', name: 'totalNilai', className: 'align-middle' },
            {data: 'nilaiAkhir', name: 'nilaiAkhir', className: 'align-middle' },
            {data: 'matematika', name: 'matematika',  className: 'align-middle'},
            {data: 'fisika', name: 'fisika', className: 'align-middle' },
            {data: 'kimia', name: 'kimia', className: 'align-middle' },
            {data: 'biologi', name: 'biologi', className: 'align-middle' },
            {data: 'sejarah', name: 'sejarah', className: 'align-middle' },
            {data: 'geografi', name: 'geografi', className: 'align-middle' },
            {data: 'keteragan', name: 'keteragan' , className: 'align-middle' },
            {data: 'action', name:'action', orderable:false, className: 'text-center'},
        ]
    });

    // $('.edit').click(function(){       
    //         $('#formModal').modal('show');
    // });

    
    // $('#dataTable').on('click','.edit', function(){       
    //         $('#formModal').modal('show');
    // });

    // $(document).on('click','.edit', function(){       
    //         $('#formModal').modal('show');
    // });

    $('#dataTable').on('click','.edit', function(){   
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url:"database/"+id+"/editNilai",
            dataType:"json",
            success:function(html){
                $('#nama').text(html.data.nis_students.nama);
                $('#matematika').val(html.data.matematika);
                $('#fisika').val(html.data.fisika);
                $('#kimia').val(html.data.kimia);
                $('#biologi').val(html.data.biologi);
                $('#sejarah').val(html.data.sejarah);
                $('#geografi').val(html.data.geografi);
                $('#hidden_id').val(html.data.nis_students.nis);
                $('.modal-title').val("Edit Nilai Akhir Siswa");
                $('#action_button').val("Edit");
                $('#formModal').modal('show');
            }
        })
    });

    $('#sample_form').on('submit', function(event){
            id = $(this).attr('id');
            event.preventDefault();
            if($('#action_button').val() == 'Edit')
            {
            $.ajax({
                url:"{{route('database.updateNilaiDetail')}} ",
              //ok, tidak bisa pake update alias tidak bisa pake route yang membutuhkan 2 request
            //   url:"('database.updateNilaiDetail', id ) }}",
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

    $(document).ready(function(){
        $('#actionColumn').removeClass("d-flex");
        // $('#actionColumn').css("width", "15px");
        console.log( "ready!xx" );    
    });



});
</script>
@endpush