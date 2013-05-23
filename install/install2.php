<?php

	if(!isset($_POST['step1'])){

		header("Location: index.php");	

		exit;

	}

	include_once 'header.php';	  

?>

		<div id="main">

			<?php echo $msg;?>

			<?php

				if($installation == 'yes'){
				}

				else{

			?>

            		<div class="wrap">

				<div class="wrap2">

					<div class="content">

					

						<div class="container">

						 <div class="content-1">

                            <div class="ins_left">

                            	<div id="stepbar">

                                    <div class="t">

                                        <div class="t">

                                            <div class="t"></div>

                                        </div>

                                    </div>

                                    <div class="m">

                                        <div>

                                            <img src="images/step1-mouseoff_205x33.png" border="0" />

                                        </div>

                                        <div>

                                            <img src="images/step2-mouseon_212x41.png" border="0" />

                                        </div>

                                        <div>

                                            <img src="images/step3-mouseoff_205x33.png" border="0" />

                                        </div>

                                        <div>

                                            <img src="images/step4-mouseoff_205x35.png" border="0" />

                                        </div>

                                        <div>

                                            <img src="images/step5-mouseoff_205x33.png" border="0" />

                                        </div>

                                    </div>

                                    <div class="b">

                                        <div class="b">

                                            <div class="b"></div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="ins_right">   

							 <form action="install3.php" name="form1" id="form1" method="post"> 

                                <TABLE width="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">

                                <TR> 

                                    <TD bgcolor="#FFFFFF" colspan="3" valign="top">

                                    <!-- End Header Code -->

                                    <br>

                                    <table border=0 width="100%" align="center">

                                    <tr>

                                    <td class="tbtext" colspan="2" align="left">

									<div style="width:100%;float:left;padding:5px;overflow-y:scroll;height:500px;border:1px solid #c4c4c4;">

									  <p><strong>RAPID RESIDUAL PRO SOFTWARE LICENSE</strong></p>

									  <p>ATTENTION:  PLEASE READ THIS DOCUMENT CAREFULLY BEFORE OPENING THIS SOFTWARE.  THE INDIVIDUAL OR ENTITY OPENING THIS SOFTWARE (THE &quot;END USER&quot;) AGREES TO BE BOUND BY THE TERMS OF THIS LICENSE.  IF YOU OPEN THIS SOFTWARE AND DO NOT AGREE TO THE TERMS OF THIS LICENSE, DO NOT USE THE SOFTWARE AND PROMPTLY RETURN THE SOFTWARE AND DELETE ALL COPIES FROM YOUR POSSESSION AND THE LICENSE PRICE WILL BE REFUNDED IN ACCORDANCE WITH OUR GUARANTEE AT THE TIME OF YOUR PURCHASE.</p>

									  <p>The enclosed computer program(s) and the accompanying documentation are provided to the End-User by REI360, LLC (&quot;Licensor&quot;) for use only under the following terms.  Licensor reserves any right not expressly granted to the End-user.  The End-User owns the disk or digital download on which the Software is recorded, but Licensor retains ownership of all copies of the Software itself.  The End-User assumes sole responsibility for the installation, use and results obtained from use of the Software.</p>

									  <p><strong>1.  License.</strong><br />

									    End-User is granted a limited, non-exclusive license to do only the following:</p>

									  <p>A.  Install and maintain the Software on licensed, personal domains for use only in the End-User's own business. </p>

									  <p>B.  Make one copy in machine-readable form solely for backup or archival purposes. </p>

									  <p>The Software is protected by copyright law.  As an express condition of this License, the End-User must reproduce on any copy Licensor's copyright notice and any other proprietary legends on the original copy supplied by Licensor.</p>

									  <p><strong>2.  Restrictions.</strong><br />

									    The End-User may NOT sublicense, assign, or distribute copies of the Software to others.  The Software contains trade secrets.  The End-User may NOT decompile, reverse engineer, disassemble, or otherwise reduce the Software to a human readable form.  THE END-USER MAY NOT MODIFY, ADAPT, TRANSLATE, RENT, LEASE, LOAN, RESELL FOR PROFIT, DISTRIBUTE, OR OTHERWISE ASSIGN OR TRANSFER THE SOFTWARE, OR CREATE DERIVATIVE WORKS BASED UPON THE SOFTWARE OR ANY PART THEREOF.</p>

									  <p><strong>3.  Protection and Security.</strong><br />

									    The End-User agrees to use its best efforts and to take all reasonable steps to safeguard the Software to ensure that no unauthorized person shall have access thereto and that no unauthorized copy, publication, disclosure or distribution in whole or in part, in any form, shall be made.  The End-User acknowledges that the Software contains valuable confidential information and trade secrets and that unauthorized use and/or copying are harmful to Licensor.<br />

                                      </p>

									  <p><strong>4.  Termination.</strong><br />

									    This License is effective until terminated.  This License will terminate immediately without notice from Licensor if the End User fails to comply with any of its provisions.  Upon termination the End User must destroy the Software and all copies thereof, and the End-User may terminate this License at any time by doing so.</p>

									  <p><strong>5.  Limited Warranty.</strong><br />

									    Licensor warrants that, for sixty (60) days from the date of initial use by the original End User, the Software shall operate substantially in accordance with the published functional specifications current at the time of purchase.  If, during the warranty period, a defect appears, End User shall notify Licensor and Licensor's only obligation shall be, at Licensor's election, to repair the defective Software or refund the purchase price.  The End-User agrees that the foregoing constitutes the End-User's sole and exclusive remedy for breach by Licensor under any warranties made under this Agreement.  This warranty does not cover any Software that has been altered or changed in any way by anyone other than Licensor.  Licensor is not responsible for problems associated with or caused by incompatible operating systems or equipment, or for problems in the interaction of the Software with software not furnished by Licensor.</p>

									  <p>No oral or written information or advice given by Licensor or its dealers, distributors, employees or agents shall in any way extend, modify or add to the foregoing warranty.</p>

									  <p>THE WARRANTY AND REMEDY PROVIDED ABOVE ARE EXCLUSIVE AND IN LIEU OF ALL OTHER WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE END-USER ASSUMES ALL RISK AS TO THE SUITABILITY, QUALITY, AND PERFORMANCE OF THE SOFTWARE.  IN NO EVENT WILL LICENSOR, OR ITS DIRECTORS, OFFICERS, EMPLOYEES OR AFFILIATES, BE LIABLE TO THE END-USER FOR ANY CONSEQUENTIAL, INCIDENTAL, INDIRECT, SPECIAL OR EXEMPLARY DAMAGES (INCLUDING DAMAGES FOR LOSS OF BUSINESS PROFITS, BUSINESS INTERRUPTION, LOSS OF DATA OR BUSINESS INFORMATION, AND THE LIKE) ARISING OUT OF THE USE OF OR INABILITY TO USE THE SOFTWARE OR ACCOMPANYING WRITTEN MATERIALS, EVEN IF LICENSOR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</p>

									  <p>LICENSOR'S LIABILITY TO THE END-USER (IF ANY) FOR ACTUAL DIRECT DAMAGES FOR ANY CAUSE WHATSOEVER, AND REGARDLESS OF THE FORM OF THE ACTION, WILL BE LIMITED TO, AND IN NO EVENT SHALL EXCEED, THE AMOUNT ORIGINALLY PAID TO LICENSOR FOR THE LICENSE OF THE SOFTWARE.</p>

									  <p><strong>7.  Enhancements.</strong><br />

									    From time to time Licensor may, in its sole discretion, advise the End-User of updates, upgrades, enhancements or improvements to the Software and/or new releases of the Software (collectively, &quot;Enhancements&quot;), and may license the End-User to use such Enhancements upon payment of prices as may be established by Licensor from time to time.  All such Enhancements to the Software provided to the End-User shall also be governed by the terms of this License.  IN ORDER FOR THE END-USER TO BE ASSURED THAT IT WILL BE ADVISED OF AND LICENSED TO USE ANY ENHANCEMENTS TO THE SOFTWARE, THE END-USER MUST BE AN ACTIVE MEMBER OF THE SOFTWARE'S MAIN WEBSITE AND MUST HAVE ACCESS TO THE MEMBERSHIP AREA OF WWW.RAPIDRESIDUALPRO.COM/MEMBER.</p>

									  <p><strong>8.  General.</strong><br />

									    This License will be governed by and construed in accordance with the laws of the North Carolina, and shall inure to the benefit of Licensor and End-User and their successors, assigns and legal representatives.  If any provision of this License is held by a court of competent jurisdiction to be invalid or unenforceable to any extent under applicable law, that provision will be enforced to the maximum extent permissible and the remaining provisions of this License will remain in full force and effect.  Any notices or other communications to be sent to Licensor must be mailed first class, postage prepaid, to the following address: REI360, LLC, attention: Steven J. Odette, President, P.O. Box 1258, Knightdale, NC 27545-1258.</p>

									  <p>This Agreement constitutes the entire agreement between the parties with respect to the subject matter hereof, and all prior proposals, agreements, representations, statements and undertakings are hereby expressly cancelled and superseded.  This Agreement may not be changed or amended except by a written instrument executed by a duly authorized officer of Licensor.</p>

									  <p><strong>9.  Acknowledgment.</strong><br />

									    BY DOWNLOADING THIS SOFTWARE, THE END-USER ACKNOWLEDGES THAT IT HAS READ THIS LICENSE, UNDERSTANDS IT, AND AGREES TO BE BOUND BY ITS TERMS AND CONDITIONS.  Should you have any questions concerning this License, contact Licensor at the address set forth above.<br />

                                      </p>

									</div>

									

									</td>

                                    </tr>

                                          <tr>

                                    <td class="tbtext" align="left" colspan="2">

								<p>

                                    <strong>Check here to acknowledge your agreement with these terms and conditions</strong> <input type="checkbox" name="agree" class="required"/><br />

                                   </p>

                                    <input type="hidden" name="step2" id="step2" value="yes" />
									<p align="right">
                                    <input type="submit" value="Proceed to Step 3" />
									</p>
                                         </td>

                                    </tr>

                                    </table>

                                     </form>

                                    <!-- Start Footer Code -->	

                                    </TD>

                                    </TR>

                                    <TR>

                                    <TD height="98" background="../images/admin/admin_red1_ftbg.jpg"><div align="right" valign="bottom" class="tbtext">

                                              </DIV></TD><TD class="copyright" colspan="3" background="../images/admin/admin_bg.jpg" valign="bottom" align="center">

                                           </TABLE>

							</div>	

<br />                  </div>

						</div>

					</div><!-- end of content -->

				</div>

			</div><!-- end of wrap -->

            <?php

				}

			?>

		</div><!-- end of main -->

<?php 

	include_once 'footer.php';

?>