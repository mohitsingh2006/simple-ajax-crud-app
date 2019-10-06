<!DOCTYPE html>
<html>
<head>
    <title>AJAX CRUD APPLICATION</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/bootstrap.min.css">
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/style.css">
</head>
<body>
<div class="header">
    <div class="container">
        <h3 class="heading">AJAX CRUD APPLICATION</h3>
    </div>    
</div>
<div class="container">
    <div class="row  pt-4">
        <div class="col-md-6">
            <h4>Car Models</h4>
        </div>
        <div class="col-md-6 text-right">
            <a href="javascript:void(0);" onclick="showModal();" class="btn btn-primary">Create</a>
        </div>

        <div class="col-md-12 pt-3">
            <table class="table table-striped" id="carModelList">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Transmission</th>
                    <th>Price</th>
                    <th>Created Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                
                <?php if(!empty($rows)){?>
                    <?php foreach($rows as $row){
                        $data['row'] = $row;
                        $this->load->view('car_model/car_row.php',$data);
                    } 
                    ?>
                <?php } else {?>
                    <tr>
                        <td>Records not found</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="createCar" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Create</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>            
            <div id="response">
                
            </div>          
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxResponseModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>            
            <div class="modal-body">
       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>            
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="deleteNow();">Yes</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

    function showModal() {
        $("#createCar").modal("show");
        $("#createCar #title").html("Create");
        $.ajax({
            url: '<?php echo base_url().'index.php/CarModel/showCreateForm'?>',
            type: 'POST',
            data:{},
            dataType: 'json',
            success : function(response){
                $("#response").html(response["html"]);
            }
        })
    }


    $("body").on("submit","#createCarModel", function(e){
        e.preventDefault();       

        $.ajax({
            url: '<?php echo base_url().'index.php/CarModel/saveModel'?>',
            type: 'POST',
            data:$(this).serializeArray(),
            dataType: 'json',
            success : function(response){                
                if (response['status'] == 0) {
                    if (response["name"] != "") {
                       $(".nameError").html(response["name"]).addClass('invalid-feedback d-block'); 
                       $("#name").addClass('is-invalid');
                    } else {
                       $(".nameError").html("").removeClass('invalid-feedback d-block'); 
                       $("#name").removeClass('is-invalid');
                    }

                    if (response["color"] != "") {
                       $(".colorError").html(response["color"]).addClass('invalid-feedback  d-block');  
                       $("#color").addClass('is-invalid');
                    } else {
                        $(".colorError").html("").removeClass('invalid-feedback d-block'); 
                        $("#color").removeClass('is-invalid');
                    }

                     if (response["price"] != "") {
                       $(".priceError").html(response["price"]).addClass('invalid-feedback  d-block'); 
                       $("#price").addClass('is-invalid');
                    } else {
                        $(".priceError").html("").removeClass('invalid-feedback d-block'); 
                        $("#price").removeClass('is-invalid');
                    }
                } else {

                    $("#createCar").modal("hide");
                    $("#ajaxResponseModal .modal-body").html(response["message"]);
                    $("#ajaxResponseModal").modal("show");

                    $(".nameError").html("").removeClass('invalid-feedback d-block'); 
                    $("#name").removeClass('is-invalid');

                    $(".colorError").html("").removeClass('invalid-feedback d-block'); 
                    $("#color").removeClass('is-invalid');

                    $(".priceError").html("").removeClass('invalid-feedback d-block'); 
                    $("#price").removeClass('is-invalid');


                    $("#carModelList").append(response["row"]);
                }

            }
        });
    });

   function showEditForm(id) {
        $("#createCar .modal-title").html('Edit');
         $.ajax({
            url: '<?php echo base_url().'index.php/CarModel/getCarModel/'?>'+id,
            type: 'POST',
            dataType: 'json',
            success : function(response){
                $("#createCar #response").html(response["html"]);
                $("#createCar").modal('show');
            }
        });
   }


   $("body").on("submit","#editCarModel", function(e){
        e.preventDefault();       

        $.ajax({
            url: '<?php echo base_url().'index.php/CarModel/updateModel'?>',
            type: 'POST',
            data:$(this).serializeArray(),
            dataType: 'json',
            success : function(response){                
                if (response['status'] == 0) {

                    if (response["name"] != "") {
                       $(".nameError").html(response["name"]).addClass('invalid-feedback d-block'); 
                       $("#name").addClass('is-invalid');
                    } else {
                       $(".nameError").html("").removeClass('invalid-feedback d-block'); 
                       $("#name").removeClass('is-invalid');
                    }

                    if (response["color"] != "") {
                       $(".colorError").html(response["color"]).addClass('invalid-feedback  d-block');  
                       $("#color").addClass('is-invalid');
                    } else {
                        $(".colorError").html("").removeClass('invalid-feedback d-block'); 
                        $("#color").removeClass('is-invalid');
                    }

                     if (response["price"] != "") {
                       $(".priceError").html(response["price"]).addClass('invalid-feedback  d-block'); 
                       $("#price").addClass('is-invalid');
                    } else {
                        $(".priceError").html("").removeClass('invalid-feedback d-block'); 
                        $("#price").removeClass('is-invalid');
                    }

                } else {

                    $("#createCar").modal("hide");
                    $("#ajaxResponseModal .modal-body").html(response["message"]);
                    $("#ajaxResponseModal").modal("show");

                    $(".nameError").html("").removeClass('invalid-feedback d-block'); 
                    $("#name").removeClass('is-invalid');

                    $(".colorError").html("").removeClass('invalid-feedback d-block'); 
                    $("#color").removeClass('is-invalid');

                    $(".priceError").html("").removeClass('invalid-feedback d-block'); 
                    $("#price").removeClass('is-invalid');

                    var id = response["row"]["id"];
                    $("#row-"+id+" .modelName").html(response["row"]["name"]);
                    $("#row-"+id+" .modelColor").html(response["row"]["color"]);
                    $("#row-"+id+" .modelTransmision").html(response["row"]["transmision"]);
                    $("#row-"+id+" .modelPrice").html(response["row"]["price"]);
                }
            }
        });
    });


   function confirmDeleteModel(id) {
        $("#deleteModal").modal("show");
        $("#deleteModal .modal-body").html("Are you sure you want to deleted #"+id+ "?");
        $("#deleteModal").data("id",id);
    }

    function deleteNow() {
        var id = $("#deleteModal").data('id');

         $.ajax({
            url: '<?php echo base_url().'index.php/CarModel/deleteModel/'?>'+id,
            type: 'POST',
            data:$(this).serializeArray(),
            dataType: 'json',
            success : function(response){                
                if (response['status'] == 1) {
                    $("#deleteModal").modal("hide");
                    $("#ajaxResponseModal .modal-body").html(response["msg"]);
                    $("#ajaxResponseModal").modal("show");
                } else {
                    $("#deleteModal").modal("hide");
                    $("#ajaxResponseModal .modal-body").html(response["msg"]);
                    $("#ajaxResponseModal").modal("show");   
                }
            }
        });
    }

</script>
</body>
</html>