<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Faq</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Faq</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['addfaq'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Faq Form</div>
					<div class="panel-body">
						<div   class="col-md-8">
							<form role="form" method="post" action="post.php?action=AddFaq">
							
								<div class="form-group">
									<label>Question Name</label>
									<input class="form-control" placeholder="Question Name" name="categoryname" onChange="categorynamer();" maxlength="30" id="categoryname">
									<span id="categorynamer" style="color:red;"></span>
								</div>
								
								<div class="form-group">
									<label>Question Answer</label>
									<textarea class="form-control" placeholder="Question Answer" name="answer" onChange="answerr();" id="answer" rows="6" style="resize:none "></textarea>
									<span id="answerr" style="color:red;"></span>
								</div>
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addcategory();" value="Add Faq"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php ?>
		
		<?php  }elseif(isset($_GET['action'])){ 
		 $query=mysql_query("select  question,answer,status,date,id from faq where id='".$_GET['catid']."'"); $category=mysql_fetch_array($query); ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Category Form</div>
					<div class="panel-body">
						<div  class="col-md-8">
							<form role="form" method="post" action="post.php?action=UpdateFaq">
							
								<div class="form-group">
									<label>Question Name</label>
									<input class="form-control" placeholder="Question Name" name="categoryname" value="<?php  echo $category['question']; ?>" maxlength="30" onChange="categorynamer();" id="categoryname">
									<input type="hidden" id="faqid" name="faqid" value="<?php echo $_GET['catid'];  ?>">
									<span id="categorynamer" style="color:red;"></span>
								</div>
								
								<div class="form-group">
									<label>Question Answer</label>
									<textarea class="form-control" placeholder="Question Answer" name="answer" onChange="answerr();" id="answer" rows="6" style="resize:none "><?php  echo $category['answer']; ?></textarea>
									<span id="answerr" style="color:red;"></span>
								</div>
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addcategory();" value="Update Faq"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="table-responsive">
		
		    <table class="table">
						    <thead>
						    <tr>
						        <th> #</th>
						        <th>Question</th>
								   <th>Answer</th>
						        <th>Date</th>
								 <th>Status</th>
								 <th  style="width:10% ">Action  <a href="faq.php?addfaq=addfaq" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</a></th>
						    </tr>
						   </thead>
						   <?php $query=mysql_query("select question,answer,status,date,id from faq order by id desc"); if(mysql_num_rows($query)>=1){ ?>
						 <tbody>
					  <?php 
					    $i=1;
						while($row=mysql_fetch_array($query)){
					   ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $row['question']; ?></td>
                           <td><?php echo $row['answer']; ?></td>
                          <td><?php echo $row['date']; ?></td>
						   <td><?php if($row['status']=='active') { ?><a href="post.php?action=faqinactive&inactive=<?php echo $row['id']; ?>" onclick="return confirm('sure to This Update Status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php }else { ?>
						   <a href="post.php?action=faqactive&active=<?php echo $row['id']; ?>" onclick="return confirm('are you sure to This Update Status');"  class="btn btn-danger" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php } ?>
						  </td>
                          <td><a href="faq.php?action=updatefaq&catid=<?php echo $row['id']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=faqdelete&delete=<?php echo $row['id']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php } ?>
                      </tbody>
					  <?php }else{ ?>
					  <tr><td colspan="6">
						<div class="alert bg-danger" role="alert">
					<svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg>Not Found
				</div></td></tr>
						<?php } ?>
						</table>
						
		</div></div>
		</div>
		<?php } ?>
		
	</div>
	<?php include('footer.php'); ?>
	<script>
	function categorynamer(){if($('#categoryname').val()==''){}else{ $('#categorynamer').html(' '); }}
	function answerr(){if($('#answer').val()==''){}else{ $('#answerr').html(' '); }}
 function addcategory(){
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		
		var categoryname=document.getElementById('categoryname').value.trim();
				if(categoryname==''){
				$("#categorynamer").html('Please enter question name');
				$("#categoryname").focus();
				return false;
				}
		var answer=document.getElementById('answer').value.trim();
				if(answer==''){
				$("#answerr").html('Please enter answer');
				$("#answer").focus();
				return false;
				}
		}
		$(document).ready(function(){
		$('.pull-right').click(function(){
		$('.bg-success').hide();
		});
		});
</script>
	
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>


