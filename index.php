<form name="form1" id='login' action='reportBuilder.php' method='POST' accept-charset='UTF-8'>
<fieldset>
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
 
<label for='username' >UserName:</label>
<input type='text' name='username' id='username'  maxlength="50" />
 
<label for='password' >Password or API token:</label>
<input type='password' name='password' id='password' maxlength="70" />
 
<input type='submit' name='Submit' value='Login' />
 
</fieldset>
</form>