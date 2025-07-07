
<!DOCTYPE html>
<html>
<head>
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>


      
        body {
                
                margin:100px;
                padding: 0;
                font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
                font-size: 14px;
                
            },
            #calendar {
             min-width: 500px;
              
                       }
           
                       .modal-title {
    color: #333;
    font-size: 24px;
    font-weight: bold;
}
.modal-content {
    background-color: white;
    border: 2px solid #ff7f0e;
    border-radius: 10px;
    color: #fff;
}

.modal-header {
    background-color: #ff7f0e;
    color: #fff;
    border-bottom: none;
    border-radius: 10px 10px 0 0;
}

.modal-title {
    font-weight: bold;
}

.modal-body {
    padding: 20px;
}

.form-control {
    margin-bottom: 10px;
    background-color: white;
    color: black;
    border: 1px solid #ff7f0e;
}

.btn-primary {
    background-color: #ff7f0e;
    border: none;
    border-radius: 5px;
    padding: 8px 20px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #ff9500;
}

.btn-danger {
    background-color: #222;
    border: 2px solid #ff7f0e;
    border-radius: 5px;
    color: #ff7f0e;
    padding: 8px 20px;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.btn-danger:hover {
    background-color: #ff7f0e;
    color: #222;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

</style> 
</head>
<body>

<div class="container"><div id="successMessage" class="alert alert-success" role="alert" style="display: none;">
  Opération réussie !
</div>
 

           
            <div class="row clearfix">
                <div class="col-md-10 column">
                <a class="btn btn-primary" href="accueil.php" role="button">back</a>
                <br />
                <br />
                <br />        <div id="calendar" style="position: relative;"></div> 
                        
                </div>
            </div>
        </div>
                     
       

<!-- Button trigger modal -->


<!-- Modal -->

<div class="modal fade" id="updateEventModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier l'événement</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateForm">
          <input type="text" id="eventTitle" class="form-control" placeholder="Nouveau titre" required><br>
          <input type="datetime-local" id="eventStartDate" class="form-control" required><br>
          <input type="datetime-local" id="eventEndDate" class="form-control" required><br>
          <button type="submit" id="updateEventButton" class="btn btn-primary">Modifier l'événement</button>
          <button type="button" class="btn btn-danger" id="deleteEventButton">Supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>



 
 <?php 
include('dbConfig.php');
$fetch_event = mysqli_query($connt, "SELECT * FROM reservs");
?>
<script>
$(document).ready(function(){
    $('#calendar').fullCalendar({
        header: {
            right: 'month, basicWeek, basicDay',
            center: 'title',
            left: 'prev, next, today'
        },
        events: [
            <?php while($result = mysqli_fetch_array($fetch_event)) { ?>
            {
                id: '<?php echo $result['id']; ?>',
                title: '<?php echo addslashes($result['title']); ?>',
                start: '<?php echo $result['start']; ?>',
                end: '<?php echo $result['end']; ?>',
                color: '#ff7f0e',
                textColor: 'black'
            },
            <?php } ?>
        ],
        editable: true,
        eventClick: function(event) {
            $('#eventTitle').val(event.title);
            $('#eventStartDate').val(event.start.format('YYYY-MM-DDTHH:mm'));
            $('#eventEndDate').val(event.end ? event.end.format('YYYY-MM-DDTHH:mm') : '');
            $('#updateEventButton').data('event-id', event.id);
            $('#deleteEventButton').data('event-id', event.id);
            $('#updateEventModal').modal('show');
        }
    });

    $('#updateForm').on('submit', function(e) {
        e.preventDefault();

        var id = $('#updateEventButton').data('event-id');
        var title = $('#eventTitle').val();
        var startDate = $('#eventStartDate').val();
        var endDate = $('#eventEndDate').val();

        console.log("ID:", id);

        $.ajax({
            url: "update.php",
            type: "POST",
            data: { id: id, title: title, startDate: startDate, endDate: endDate },
            success: function(response) {
                console.log("Update response:", response);
                if (response.includes("success")) {
                    $('#updateEventModal').modal('hide');
                    location.reload();
                } else {
                    alert("Erreur lors de la mise à jour: " + response);
                }
            }
        });
    });

    $('#deleteEventButton').on('click', function() {
        var id = $(this).data('event-id');
        if (confirm("Voulez-vous vraiment supprimer cet événement ?")) {
            $.ajax({
                url: "delete.php",
                type: "POST",
                data: { id: id },
                success: function() {
                    $('#updateEventModal').modal('hide');
                    location.reload();
                }
            });
        }
    });
});
</script>
</body>
</html>
