<?php

  //Default styles
  $backgroundColor = '#195D00';
  
  $projectTextColour = '#FFFFF';
  $clientTextColour = '#FFFFF';
  
  $tabBackground = '#217A00';
  $tabBackgroundHover = '#054700';
  
  $tabTextColour = '#D1EEFF';
  $tabTextColourHover = '#FFFFF';
  $tabTextColourActive = '#000000';
  
  $linkTextColour = '#03C';
  
  //Overwrite if they exist
  if($accountStyles = $session->read('AuthAccount.AccountStyle'))
  {
    foreach($accountStyles as $key => $val)
    {
      ${$key} = $val;
    }
  }

?>
header { background-color:<?php echo $backgroundColor; ?> }
header h1 { color:<?php echo $projectTextColour; ?>; }
header h1 span { color:<?php echo $clientTextColour; ?>; }

nav.tabs li a { background-color:<?php echo $tabBackground; ?>; }
nav.tabs li a { color:<?php echo $tabTextColour; ?>; }
nav.tabs li a:hover { background-color:<?php echo $tabBackgroundHover; ?>; }
nav.tabs li a:hover { color:<?php echo $tabTextColourHover; ?>; }
nav.tabs li.active a { color:<?php echo $tabTextColourActive; ?>; }

a { color:<?php echo $linkTextColour; ?>; }
a:hover { background-color:<?php echo $linkTextColour; ?>; }
