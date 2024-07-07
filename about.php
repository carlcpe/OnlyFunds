<?php 
include('header.php'); 
checkUser();
userArea();?>
<style>

   .image{
      width: 100%;
      height: 300px;
      display:flex;
      justify-content: center;
      align-items: center;
   }
   .image img{
      height: 300px;
      width: 300px;
      border-radius: 50%;
      border:10px solid;
   }
   .team-member{
      text-align: center;
   }
</style>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header wallet-header-black">
                     <h1>About OnlyFunds</h1>
                  </div>
                  <div class="card-body">
                     <p>Welcome to OnlyFunds, your trusted partner in financial management. At OnlyFunds, we believe in empowering individuals and businesses alike to take control of their finances with ease and confidence. Whether you're tracking personal expenses, managing investments, or handling business finances, OnlyFunds provides the tools and insights you need to make informed financial decisions.</p>

                     <h2>Our Mission</h2>
                     <p>Our mission at OnlyFunds is to simplify financial management through intuitive tools and comprehensive resources. We strive to:</p>
                     <ul class="indented-list">
                        <li><strong>Empower Users:</strong> We empower users with easy-to-use interfaces and powerful features that streamline financial tasks.</li>
                        <li><strong>Promote Financial Health:</strong> We promote financial health by offering insights into spending patterns, savings opportunities, and investment strategies.</li>
                        <li><strong>Build Trust:</strong> We build trust through transparency, security, and reliability in handling your financial data.</li>
                     </ul>

                     <h2>What We Offer</h2>
                     <ul class="indented-list">
                        <li><strong>Expense Tracking:</strong> Easily track your expenses and categorize spending to gain a clear understanding of your financial habits.</li>
                        <li><strong>Income Management:</strong> Manage multiple income sources and track earnings over time to optimize budgeting and savings.</li>
                        <li><strong>Wallet Management:</strong> Maintain a clear view of your wallet balance with real-time updates and transaction history.</li>
                        <li><strong>Financial Insights:</strong> Gain valuable insights through charts and reports that highlight trends and areas for financial improvement.</li>
                     </ul>

                     <h2>Why Choose OnlyFunds?</h2>
                     <ul class="indented-list">
                        <li><strong>User-Centric Design:</strong> We prioritize user experience, ensuring our platform is accessible and intuitive for users of all levels of financial expertise.</li>
                        <li><strong>Security:</strong> Your financial data is protected with industry-standard security measures to ensure peace of mind.</li>
                        <li><strong>Continuous Improvement:</strong> We continuously update and improve our platform based on user feedback and evolving financial practices.</li>
                     </ul>

                     <p>Join thousands of users who trust OnlyFunds to manage their finances effectively. Whether you're planning for the future, optimizing your budget, or monitoring business expenses, OnlyFunds is here to support your financial journey.</p>
                     <p>Discover the power of smart financial management with OnlyFunds. Start today and take control of your financial future.</p>
                  </div>
               </div>
            </div>
         </div>

         <!-- Team Members Section -->
         <div class="row mt-4">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header wallet-header-black">
                     <h2>Our Team</h2>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <!-- Team Member 1 -->
                        <div class="col-md-4 mb-4">
                           <div class="team-member">
                              <div class="image">
                                 <img src="media/team-member11.jpg" alt="Team Member 1" class="img-fluid">
                              </div>

                              <h4>Christofer Estrada</h4>
                              <p>Backend Specialist</p>
                           </div>
                        </div>

                        <!-- Team Member 2 -->
                        <div class="col-md-4 mb-4">
                              <div class="team-member">
                                 <div class="image">
                              <img src="media/team-member21.jpg" alt="Team Member 2" class="img-fluid">
                              </div>
                              <h4>Karl Cedrick Salonga</h4>
                              <p>Project Head</p>
                           </div>
                        </div>

                        <!-- Team Member 3 -->
                        <div class="col-md-4 mb-4">
                              <div class="team-member">
                                 <div class="image">
                              <img src="media/team-member31.png" alt="Team Member 3" class="img-fluid">
                              </div>
                              <h4>Carl Jendreik Naval</h4>
                              <p>Frontend Specialist</p>
                           </div>
                        </div>

                        <!-- Team Member 4 -->
                        <div class="col-md-4 mb-4">
                           <div class="team-member">
                              <div class="image">
                                 <img src="media/team-member41.jpg" alt="Team Member 4" class="img-fluid">
                              </div>
                              <h4>Ivan Aron Simon</h4>
                              <p>Frontend Support</p>
                           </div>
                        </div>

                        <!-- Team Member 5 -->
                        <div class="col-md-4 mb-4">
                           <div class="team-member">
                              <div class="image">
                                 <img src="media/team-member5.jpg" alt="Team Member 5" class="img-fluid">
                              </div>
                              <h4>Gabriel Natividad</h4>
                              <p>Content Specialist</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</div>

<?php include('footer.php'); ?>
