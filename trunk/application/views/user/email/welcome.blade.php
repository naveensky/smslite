<p>Hi User,</p>

<p>Thank you for signning up for MSNGR!</p>

<p>Please <a href="<% URL::to('user/activate/' . $result->emailVerificationCode); %>">click here</a> to confirm your
    email address.</p>

<p>If clicking the link above does not work, copy and paste the following URL in a new browser window instead. <br>
    <% URL::to('user/activate/' . $result->emailVerificationCode);%></p>

<p>It is also a good idea to add <% Config::get('email.from_email') %> to your address book to ensure that you receive
    our messages (no
    spam, we promise!)</p>

<p>All the best</p>
<p>MSNGR Team</p>
<hr>
<small style="color:#aaaaaa">
    <p>
        This email was sent to <% $result->email %>, which was used to register for an account at http://www.msngr.in.
        It
        is deemed necessary communication and is not part of any newsletter or promotional offer.
    </p>

    <p> Please DO NOT REPLY to this message - it is an automated email and your reply will not be received. For more
        information, please contact MSNGR Customer Support.
    </p>

    <p>
        <a href="http://msngr.in/privacy">Privacy Policy</a>
    </p></small>