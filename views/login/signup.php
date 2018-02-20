<h1>Registrieren</h1>


<form name="signUpForm" method="POST" action="">
	<table>
		<tr>
			<td>Benutzername:<br>
				<small>Erlaubt sind Buchstaben,Zahlen und"_"</small>
			</td>
			<td><input type="text" name="username" required pattern="[A-Za-z0-9_äÄöÖüÜß]+"></td>
		</tr>
		<tr>
			<td>Passwort:<br>
				<small>mindestens 6 Zeichen</small></td>
			<td><input type="password" name="password" required minlength="6"></td>
		</tr>
		<tr>
			<td>Passwort wiederholen:</td>
			<td><input type="password" name="password_rep" required  minlength="6"></td>
		</tr>
		<tr>
			<td>E-Mailadresse:</td>
			<td><input type="email" name="mail" required></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="signup" value="registrieren"></td>
		</tr>
	</table>
</form>