<?php include('header.php');
$user = getPermissions('about_us');
if($user['view'] != '1')
{
	echo '<script type="text/javascript">window.location = "page_not_found.php"</script>';
}
?>
<style type="text/css">
.fontstyles p, .fontstyle span, ul.team li {font-size: 14px; line-height:22px!important;}
</style>
<div id="content">
    <div id="main">
        <div class="full_w" style="min-height:400px;" >
           <div class="h_title">About Us</div>
             <div class="custtable_left fontstyles" style="margin-left:20px">
				<p align="justify">
				<strong>Barna Business School</strong>, the first School of Business at the Dominican Republic and the Caribbean, was founded by a group of young leaders and entrepreneurs international training, to transform the Latin American business vision through management training.</span></span><span><span class="style6">			    To do this, Barna cultivates a contemporary academic philosophy, independent, and practice-oriented value creation, with a strong component of ethics and social responsibility.Search directly influence for the School, its members and business related:</span>
			    </span>			   </p>
				<p>&bull; Generate power and influence through higher education.</p>
				   <p>&bull; Get close to the needs of Dominican business and the Strategic Plan of the country.</p>
				   <p>&bull; the &quot;Dominican ideas' are more easily promote international business based on credibility.</p>
				   <p>&bull; Increase the credibility of higher education and thus be increased the enrollment in programs for senior management.</p>
				   <p>&bull; Can attract international donations for the sector of higher education more easily.</p>
				   <p>&bull; Can choose to grant infrastructure.</p>
				   <p>&bull; They become a focus of international reference of higher education that adequately represent Dominican Republic as a generator of significant changes in the global map.</p>
				   <p><br />
				   <strong>Our Technology Experts:</strong>
				   <ul class="team">
				   <li>Ravendra Singh</li>
				   <li>Kalicharan Sikarwar</li>
				   <li>Deepali kakkar</li>
				   <li>Dwarikesh Sharma</li>
				   <li>Tanaya Vashisth</li>
				   </ul>
				  </p>
				</p>
				 
		  </div>
				 <div class="clear"></div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
