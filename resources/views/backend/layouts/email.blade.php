<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta content='text/html; charset=utf-8' http-equiv='Content-Type'>
	<meta content='width=device-width, initial-scale=1.0' name='viewport'>
	<title></title>
	<style>
	  #outlook a {padding:0;}
	  body{
	    width:100% !important;
	    -webkit-text-size-adjust:100%;
	    -ms-text-size-adjust:100%;
	    background: #fafafa;
	    margin:0;
	    padding:0;
	    font-size: 1em;
	  }
	  .ExternalClass {width:100%;}
	  .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
	  #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;
	    background: #fafafa;
	    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	  }

	  #innerTable{
	    border: 3px solid #eee;
	    background: #fff;
	    margin: 20px;
	    color: #555;
	  }

	  #innerTable td{
	    padding: 20px;
	  }

	  td.email-title{
	    font-size: 24px;
	    color: #999;
	  }

	  table#innerTable tr.with-separator td{
	    border-bottom: 1px solid #eee;
	  }

	  img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
	  a img {border:none;}
	  .image_fix {display:block;}

	  p {margin: 0 0 1em 0;}

	  h1, h2, h3, h4, h5, h6 {color: black !important;}
	  h3 {
	    border-bottom: 1px dotted #ccc;
	    margin-bottom: 5px !important;
	    padding-bottom: 5px;
	  }

	  h4, h5, h6{margin-bottom: 0 !important;}

	  h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

	  h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
	  color: red !important;
	  }

	  h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
	  color: purple !important;
	  }
	  table td {border-collapse: collapse; line-height: 20px;}
	  table {
	    border-collapse:collapse;
	    mso-table-lspace:0pt;
	    mso-table-rspace:0pt;
	  }

	  a {color: #0064BE;}

	  ul{
	    margin-top: 0;
	  }


	  /***************************************************
	  					MOBILE TARGETING
	  ***************************************************/

	  @media only screen and (max-device-width: 480px) {
	    /* Part one of controlling phone number linking for mobile. */
	    a[href^="tel"], a[href^="sms"] {
	          text-decoration: none;
	          color: blue; /* or whatever your want */
	          pointer-events: none;
	          cursor: default;
	        }

	    .mobile-link a[href^="tel"], .mobile-link a[href^="sms"] {
	          text-decoration: default;
	          color: orange !important;
	          pointer-events: auto;
	          cursor: default;
	        }

	  }

	  /* More Specific Targeting */

	  @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
	  /* You guessed it, ipad (tablets, smaller screens, etc) */
	    /* repeating for the ipad */
	    a[href^="tel"], a[href^="sms"] {
	          text-decoration: none;
	          color: #0064BE; /* or whatever your want */
	          pointer-events: none;
	          cursor: default;
	        }

	    .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
	          text-decoration: default;
	          color: #0064BE !important;
	          pointer-events: auto;
	          cursor: default;
	        }
	  }

	  @media only screen and (-webkit-min-device-pixel-ratio: 2) {
	  /* Put your iPhone 4g styles in here */
	  }

	  /* Android targeting */
	  @media only screen and (-webkit-device-pixel-ratio:.75){
	  /* Put CSS for low density (ldpi) Android layouts in here */
	  }
	  @media only screen and (-webkit-device-pixel-ratio:1){
	  /* Put CSS for medium density (mdpi) Android layouts in here */
	  }
	  @media only screen and (-webkit-device-pixel-ratio:1.5){
	  /* Put CSS for high density (hdpi) Android layouts in here */
	  }
	  /* end Android targeting */
	</style>
</head>
<body>
	<table border='0' cellpadding='0' cellspacing='0' id='backgroundTable' style='margin:0; padding:0; width:100% !important; line-height: 100% !important;background: #fafafa;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 0.9em;'>
		<tr>
			<td valign='top' align='center'>
				<table align='center' border='0' cellpadding='0' cellspacing='0' id='innerTable' style='border: 3px solid #eee;background: #fff;margin: 20px;color: #555;'>
					<tr class='with-separator'>
						<td style='border-bottom: 1px solid #eee;padding:20px' valign='middle' width='204'>

							@yield('logo')

						</td>
						<td align='right' class='email-title' style='font-size: 24px;color: #999;border-bottom: 1px solid #eee;padding:20px' valign='middle' width='200'>

							@yield('subject')

						</td>
					</tr>
					<tr class='main-content' style='color:#555'>
						<td colspan='2' style='padding:20px'>

							@yield('content')

						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>