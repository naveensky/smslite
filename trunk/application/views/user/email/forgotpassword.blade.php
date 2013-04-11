<p>Hi User,</p>

<p>We have received a password reset request for a MSNGR account associated with this email.</p>

<p>If you have initiated the request please <a href="<% URL::to('user/reset_password/' . $result->forgottenPasswordCode); %>">click here</a> to reset your password.</p>

<p>If clicking the link above does not work, copy and paste the following URL in a new browser window instead. <br>
    <% URL::to('user/reset_password/' . $result->forgottenPasswordCode); %></p>

<p>In case you have not initiated such a request, you can safely ignore this email.</p>

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