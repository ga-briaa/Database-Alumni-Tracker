<?php
    include 'db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DU - Alumni Tracker</title>
        <link rel="stylesheet" href="global.css">
    </head>

    <body>
        <!--Header-->
        <div class="header">
            <a href="#default">
                <img src="../assets/DU-Logo-with-text.png" alt="DU Logo" class="logo">
                <!-- insert image of logo -->
            </a>
            <div class="header-tabs">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="survey.php">Survey</a>
            </div>
            <div class="header-right-text">
                <p>Database - Alumni Tracker</p>
            </div>
        </div>

        <p>
            testing
        </p>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec venenatis auctor ante, facilisis placerat ex viverra vitae. Phasellus non venenatis risus, eget convallis velit. Duis eu mauris laoreet, sagittis tortor vitae, rhoncus sem. Nulla nec sodales justo. Donec congue enim id nibh lacinia, vitae euismod est fermentum. Nam vel metus est. Sed dictum suscipit purus, vel egestas neque feugiat in. Praesent quam neque, suscipit id interdum lacinia, dictum non velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Integer efficitur nisl eget ligula efficitur fermentum. Aliquam vitae suscipit orci, ac cursus lectus. Nam rutrum augue eros, quis ultrices est malesuada accumsan. Mauris diam metus, semper et nulla eget, fringilla ornare lacus. Suspendisse mauris purus, ornare vel sollicitudin vel, facilisis ut nisi. Curabitur tristique mauris in sagittis interdum.

Etiam sit amet hendrerit nulla, sed lobortis libero. Nunc vitae egestas velit, sed eleifend neque. Vestibulum eget leo ornare, varius purus et, cursus nulla. Morbi gravida, sem vel feugiat luctus, turpis ipsum iaculis velit, eu molestie risus nisl pharetra quam. Vivamus faucibus nulla at nulla laoreet, vitae eleifend elit suscipit. Sed nibh augue, aliquet at condimentum vehicula, convallis et erat. Nam venenatis diam a justo hendrerit, id commodo nulla accumsan. Nunc consectetur purus dui, a mattis quam cursus vitae. Nam eleifend convallis cursus.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque at vehicula diam. Pellentesque nec cursus ipsum, sed accumsan leo. Suspendisse hendrerit magna quis euismod semper. Nam viverra urna et accumsan luctus. Donec mauris odio, consectetur nec magna id, ultrices tristique nulla. Morbi ultrices arcu elit, id sagittis arcu pharetra id. Vestibulum eu tempus sem.

Suspendisse in sollicitudin lorem. Aliquam malesuada ac dolor in porta. Maecenas est sem, pellentesque sit amet elit at, venenatis lobortis sapien. Nulla massa augue, dapibus vitae sem sed, blandit cursus lacus. Donec sed erat porta, fermentum sapien id, feugiat metus. Curabitur posuere, tellus eget accumsan efficitur, ligula ex eleifend dui, vel posuere augue magna non elit. Sed augue nisl, lacinia eu sem scelerisque, mattis eleifend lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum a auctor lectus. Etiam sed orci gravida, maximus sapien eget, sodales metus. Curabitur condimentum nisi mauris, vitae maximus erat ultrices at. Phasellus sit amet quam vitae erat tristique dapibus. Pellentesque ut justo in purus pharetra placerat ac et tellus. Nam aliquam, mi sit amet ullamcorper malesuada, justo eros mollis felis, nec facilisis leo nibh id mauris.

Proin et lobortis sapien. Sed consequat vel dui vel commodo. Mauris vehicula lorem in sem lacinia facilisis. Sed nulla massa, porttitor quis mi eu, finibus placerat neque. Etiam dignissim ex sed metus mollis, non tincidunt tellus sagittis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus tempor sapien dictum accumsan mollis. Nulla aliquet vel velit a dictum. Suspendisse tincidunt lobortis odio vitae congue.

Mauris malesuada ligula ipsum, malesuada lobortis nibh mollis in. Duis elementum metus sed sagittis lacinia. Phasellus aliquet nisl blandit elit sodales egestas. Praesent sit amet velit odio. Proin pellentesque turpis a tellus iaculis interdum. Nullam a tortor viverra, tincidunt orci vitae, pulvinar arcu. Suspendisse faucibus mauris convallis, porttitor magna vitae, euismod elit. Praesent in lectus ipsum. Nullam facilisis magna ut hendrerit rutrum. Aliquam vel ex eu urna vulputate tempor eget a odio. Praesent quis nisi non ex laoreet luctus. Donec vel massa in lectus porttitor vulputate. Vivamus vitae finibus odio.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec venenatis auctor ante, facilisis placerat ex viverra vitae. Phasellus non venenatis risus, eget convallis velit. Duis eu mauris laoreet, sagittis tortor vitae, rhoncus sem. Nulla nec sodales justo. Donec congue enim id nibh lacinia, vitae euismod est fermentum. Nam vel metus est. Sed dictum suscipit purus, vel egestas neque feugiat in. Praesent quam neque, suscipit id interdum lacinia, dictum non velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Integer efficitur nisl eget ligula efficitur fermentum. Aliquam vitae suscipit orci, ac cursus lectus. Nam rutrum augue eros, quis ultrices est malesuada accumsan. Mauris diam metus, semper et nulla eget, fringilla ornare lacus. Suspendisse mauris purus, ornare vel sollicitudin vel, facilisis ut nisi. Curabitur tristique mauris in sagittis interdum.

Etiam sit amet hendrerit nulla, sed lobortis libero. Nunc vitae egestas velit, sed eleifend neque. Vestibulum eget leo ornare, varius purus et, cursus nulla. Morbi gravida, sem vel feugiat luctus, turpis ipsum iaculis velit, eu molestie risus nisl pharetra quam. Vivamus faucibus nulla at nulla laoreet, vitae eleifend elit suscipit. Sed nibh augue, aliquet at condimentum vehicula, convallis et erat. Nam venenatis diam a justo hendrerit, id commodo nulla accumsan. Nunc consectetur purus dui, a mattis quam cursus vitae. Nam eleifend convallis cursus.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque at vehicula diam. Pellentesque nec cursus ipsum, sed accumsan leo. Suspendisse hendrerit magna quis euismod semper. Nam viverra urna et accumsan luctus. Donec mauris odio, consectetur nec magna id, ultrices tristique nulla. Morbi ultrices arcu elit, id sagittis arcu pharetra id. Vestibulum eu tempus sem.

Suspendisse in sollicitudin lorem. Aliquam malesuada ac dolor in porta. Maecenas est sem, pellentesque sit amet elit at, venenatis lobortis sapien. Nulla massa augue, dapibus vitae sem sed, blandit cursus lacus. Donec sed erat porta, fermentum sapien id, feugiat metus. Curabitur posuere, tellus eget accumsan efficitur, ligula ex eleifend dui, vel posuere augue magna non elit. Sed augue nisl, lacinia eu sem scelerisque, mattis eleifend lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum a auctor lectus. Etiam sed orci gravida, maximus sapien eget, sodales metus. Curabitur condimentum nisi mauris, vitae maximus erat ultrices at. Phasellus sit amet quam vitae erat tristique dapibus. Pellentesque ut justo in purus pharetra placerat ac et tellus. Nam aliquam, mi sit amet ullamcorper malesuada, justo eros mollis felis, nec facilisis leo nibh id mauris.

Proin et lobortis sapien. Sed consequat vel dui vel commodo. Mauris vehicula lorem in sem lacinia facilisis. Sed nulla massa, porttitor quis mi eu, finibus placerat neque. Etiam dignissim ex sed metus mollis, non tincidunt tellus sagittis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus tempor sapien dictum accumsan mollis. Nulla aliquet vel velit a dictum. Suspendisse tincidunt lobortis odio vitae congue.

Mauris malesuada ligula ipsum, malesuada lobortis nibh mollis in. Duis elementum metus sed sagittis lacinia. Phasellus aliquet nisl blandit elit sodales egestas. Praesent sit amet velit odio. Proin pellentesque turpis a tellus iaculis interdum. Nullam a tortor viverra, tincidunt orci vitae, pulvinar arcu. Suspendisse faucibus mauris convallis, porttitor magna vitae, euismod elit. Praesent in lectus ipsum. Nullam facilisis magna ut hendrerit rutrum. Aliquam vel ex eu urna vulputate tempor eget a odio. Praesent quis nisi non ex laoreet luctus. Donec vel massa in lectus porttitor vulputate. Vivamus vitae finibus odio.
        </p>
        
    </body>
</html>