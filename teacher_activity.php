<?php include('header.php'); ?>
<div id="content">
    <div id="main">
        <div class="full_w">
            <div class="h_title">Teacher Activity</div>
            <form action="" method="post">
                <div class="custtable_left">
                    <div class="custtd_left">
                        <h2>Program<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctProgram" name="slctProgram" class="select1">
                            <option value="" selected="selected">--Select Program--</option>
                            <option value="MBA">MBA</option>
                            <option value="MCA">MCA</option>
                            <option value="BTech">BTech</option>
                            <option value="MTech">MTech</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Subject <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctSubject" name="slctSubject" class="select1">
                            <option value="" selected="selected">--Select Subject--</option>
                            <option value="Physics">Physics</option>
                            <option value="Checmistry">Checmistry</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Micro Computer">Micro Computer</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Teacher <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctTeacher" name="slctTeacher"  class="selectMultiple"  multiple >
                            <option value="Dwarikesh Sharma">Dwarikesh Sharma</option>
                            <option value="Ravendra Singh">Ravendra Singh</option>
                            <option value="Kalicharan Sikarwar">Kalicharan Sikarwar</option>
                            <option value="Deepali Kakkar">Deepali Kakkar</option>
                            <option value="Tanaya Vashisth">Tanaya Vashisth</option>
                            <option value="Luis Rao">Luis Rao</option>
                            <option value="Navish Nirwania">Navish Nirwania</option>
                        </select> 
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h3><span class="redstar">*</span>All Fields are mandatory.</h3>
                    </div>
                    <div class="txtfield">
                        <input type="button" name="btnAdd" class="buttonsub" value="Add Activity">
                    </div>
                    <div class="txtfield">
                        <input type="button" name="btnCancel" class="buttonsub" value="Cancel">
                    </div>
                </div>	
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

