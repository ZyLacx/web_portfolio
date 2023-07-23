<?php
if(!defined("AllowAccess")){
    header("Location: ../index.php?tabory");
    exit();
}

if(isset($_GET["tabor"])){
	switch($_GET["tabor"]){
		case "jar":
			include_once "pages/tabory/jar.php";
			break;
		case "leto":
			include_once "pages/tabory/leto.php";
			break;
		case "chlieb":
			include_once "pages/tabory/chlieb.php";
			break;
		case "pizza":
			include_once "pages/tabory/pizza.php";
			break;
		case "jablko":
			include_once "pages/tabory/jablko.php";
			break;
		case "parohy":
			include_once "pages/tabory/parohy.php";
			break;
		case "karate":
			include_once "pages/tabory/karate.php";
			break;
		case "mladez":
			include_once "pages/tabory/mladez.php";
			break;
		case "vianoce":
			include_once "pages/tabory/vianoce.php";
			break;
	}
}
else{
?>
<div class="flex">
	<div class="links">
		<a href="?stranka=tabory&tabor=jar" >
			<img src="files/jar.png" alt="" class="linksImg">
			<p >Vítanie jari</p>
			<!--<p class="linksPopis"></p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=leto" >
			<img src="files/leto.png" alt="" class="linksImg">
			<p >Letná škola</p>
			<!--<p class="linksPopis"></p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=chlieb" >
			<img src="files/chlieb.png" alt="" class="linksImg">
			<p >Chlebový víkend</p>
			<!--<p class="linksPopis">Chlieb náš každodenný...</p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=pizza" >
			<img src="files/pizza.png" alt="" class="linksImg">
			<p >Pizza víkend</p>
			<!--<p class="linksPopis"></p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=jablko" >
			<img src="files/jablko.png" alt="" class="linksImg">
			<p >Jablkový víkend</p>
			<!--<p class="linksPopis">Jedno jablko denne a lekár si k vám nenájde cestu</p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=parohy" >
			<img src="files/parohy.png" alt="" class="linksImg">
			<p >Hľadanie parohov</p>
			<!--<p class="linksPopis">Prechádzky lesmi prinášajú veľa pokladov</p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=karate" >
			<img src="files/karate.png" alt="" class="linksImg">
			<p >Karate sústredenia</p>
			<!--<p class="linksPopis"></p>!-->
		</a>
	</div>
	<div class="links">
		<a href="?stranka=tabory&tabor=mladez" >
			<img src="files/mladez.png" alt="" class="linksImg">
			<p >Mládežnícky víkend</p>
			<!--<p class="linksPopis"></p>!-->
		</a>
	</div>
	<div class="links last">
			<a href="?stranka=tabory&tabor=vianoce">
				<img src="files/vianoce2.png" alt="" class="linksImg">
				<p>Vianoce</p>
				<!--<p class="linksPopis"></p>!-->
			</a>
	</div>
</div>
<?php
}