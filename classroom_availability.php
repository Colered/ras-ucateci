<?php include('header.php'); ?>
<div id="content">
    <div id="main">
        <div class="full_w">
            <div class="h_title">Classroom Availability</div>
            <form action="" method="post" class="form-align">
                <div class="custtable_left">
                    <div class="custtd_left">
                        <h2>Room Type <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctRmType" name="slctRmType" class="select1">
                            <option value="" selected="selected">--Select Room Type--</option>
                            <option value="Type1">Type1</option>
                            <option value="Type2">Type2</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Room Name <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                         <select id="slctRmName" name="slctRmName" class="select1">
                            <option value="" selected="selected">--Select Room Name--</option>
                            <option value="Room1">Room1</option>
                            <option value="Room2">Room2</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Time Interval <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        From:<input type="text" size="12" id="fromclsRmAval" />
                        To:<input type="text" size="12" id="toclsRmAval" />
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Days<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="checkbox" id="Mon" name="Mon" /> Mon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="Tue" name="Tue" /> Tue &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="Wed" name="Wed" /> Wed &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="Thu" name="Thu" /> Thu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="Fri" name="Fri" /> Fri &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="Sat" name="Sat" /> Sat 
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Timeslots.<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                        <select id="slctTs" name="slctTs" class="ts-avail" multiple="multiple">
                            <option value="9-10">9-10</option>
                            <option value="10-11">10-11</option>
                            <option value="11-12">11-12</option>
                            <option value="12-1">12-1</option>
                            <option value="1-2">1-2</option>
                            <option value="2-3">2-3</option>
                            <option value="3-4">3-4</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Exception.<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" size="12" id="exceptnClsAval" />
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h3><span class="redstar">*</span>All Fields are mandatory.</h3>
                    </div>
                    <div class="txtfield">
                        <input type="button" name="btnSave" class="buttonsub" value="Save">
                    </div>
                    <div class="txtfield">
                        <input type="button" name="btnCancel" class="buttonsub" value="Cancel">
                    </div>
                </div>	
                <div class="custtable_left div-arrow-img">
                    <img src="images/arrow.png" id="arrow-img" class="arrow-img" />
                </div>
                <div class="custtable_left divRule" >
                    <select id="rules" name="rules" multiple="multiple"  class="rule">
                        <option value="9-10">Rule1</option>
                        <option value="27-05-2014 to 27-07-2014 Mon 9-10">27-05-2014 to 27-07-2014 Mon 9-10</option>
                        <option value=">27-05-2014 to 27-07-2014 Tue 10-11">27-05-2014 to 27-07-2014 Tue 10-11</option>
                        <option value="27-05-2014 to 27-07-2014 Wed 11-12">27-05-2014 to 27-07-2014 Wed 11-12</option>
                        <option value="27-05-2014 to 27-07-2014 Mon 9-10">27-05-2014 to 27-07-2014 Mon 9-10</option>
                        <option value=">27-05-2014 to 27-07-2014 Tue 10-11">27-05-2014 to 27-07-2014 Tue 10-11</option>
                        <option value="27-05-2014 to 27-07-2014 Wed 11-12">27-05-2014 to 27-07-2014 Wed 11-12</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

