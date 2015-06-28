<script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
<script src="js/libs/jquery-1.11.3.min.js"></script>
<script src="js/libs/underscore-min.js"></script>
<script src="js/libs/bootstrap.min.js"></script>
<script src="js/libs/ripples.min.js"></script>
<script src="js/libs/material.min.js"></script>

<script>
  $.material.init();
</script>

<script type="text/template" id="item-tpl">
    <tr><td><%=land.k%></td><td><%=land.v%></td></tr>
</script>

<script type="text/template" id="content-tpl">

<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    X
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                         <table class="table table-striped">
                         <%=_.reduce(land_info,function(content, land){
                           var content_compiled = _.template($('#item-tpl').html());
                           var land_content = content_compiled({land:land});
                           return content+land_content;
                         },"")%>
                         </table> 
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->
</script>