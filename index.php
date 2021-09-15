
<?php
//index.php

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Comment System</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <style>
        #grad1 {
            background-image: linear-gradient(45deg, cyan, rgb(7, 50, 172));
            }
        h2{
            color: white;
            text-align: center;
        }
        label{
            color: white;
        }
        </style>
    </head>
    <body>
    <div id="grad1">
        <br />
        <h2 >Comment System</a></h2>
        <br />
        <div style="background-color: rgba(0, 18, 46, 0.65); 
        margin: auto; width: 80%; 
        padding: 15px; 
        border-radius: 5px; 
        border: 5px solid rgba(0, 12, 40, 0.4);">
            <form method="POST" id="comment_form">
            <div class="form-row">
                <div class="form-group row">
                    <label for="email" class="col-sm-1 col-form-label">Email*:</label>
                    <div class="col-md-4">
                        <input type="email" name="email" id="email" class="form-control"/>
                    </div>
                    <label for="text" class="col-sm-1 col-form-label">Name*:</label>
                    <div class="col-md-4">
                        <input type="text" name="comment_name" id="comment_name" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="comment_content" class="col-sm-1 col-form-label">Comment*:</label>
                    <div class="col-md-9">
                        <textarea name="comment_content" id="comment_content" class="form-control" rows="5"></textarea>
                    </div>
                </div>
            </div>
                <div class="form-group">
                    <input type="hidden" name="id" id="id" value="0" />
                    <div class="text-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />
                </div></div>
            </form></div>
            <span id="comment_message"></span>
            <br />
            <div id="display_comment"></div>
        </div>
    </body>
    </html>
    
    <script>
    $(document).ready(function()
    {
        
        $('#comment_form').on('submit', function(event)
        {
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"add_comment.php",
                method:"POST",
                data:form_data,
                dataType:"JSON",
                success:function(data)
                {
                    if(data.error != '')
                    {
                        $('#comment_form')[0].reset();
                        $('#comment_message').html(data.error);
                        $('#id').val('0');
                        load_comment();
                    }
                }
            })
        });
        
        load_comment();
        
        function load_comment()
        {
            $.ajax({
                url:"fetch_comment.php",
                method:"POST",
                success:function(data)
                {
                    $('#display_comment').html(data);
                }
            })
        }
        
        $(document).on('click', '.reply', function(){
            var id = $(this).attr("id");
            $('#id').val(id);
            $('#email').focus();
            });
            
    });
    </script>


