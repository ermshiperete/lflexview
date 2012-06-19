<div id="padding">
<h2>Search Results</h2>
<div id="searchResults">
<? $i = 0; if ($SearchResults) foreach($SearchResults as $result):
	$lexicalUnit = $result->getSpace('lexical-unit'); ?>
<div class="<? echo "r".$i%2; if ($i==0) echo " rFirst"; ?>">
	<div class="meanings">
	<? echo $lexicalUnit->get(LANG_IPA); ?>
	</div>
	<a href="<?php echo $urlMapper->write(ActionPath::fromString('Page/LexicalEntry'), new Command('read', array($result->getID()))); ?>"><? echo $lexicalUnit->get(LANG_Vernacular); ?></a>
</div>
<? $i++; endforeach; ?>
</div>
</div>
