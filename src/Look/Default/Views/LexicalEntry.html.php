<div id="padding">
<div><?php $lexicalUnit = $LexicalEntry->getSpace('lexicalUnit'); ?>
<h1 class="headWord"><?php echo $lexicalUnit->get(LANG_Vernacular);?></h1>
<p class="ipa"><?php echo $lexicalUnit->get(LANG_IPA);?></p>
<?php $i = 1; $senses = $LexicalEntry->getSpace('senses'); if ($senses) foreach($senses as $sense): ?>
<?php $partOfSpeech = $sense->get('partOfSpeech');?>
<p class="partOfSpeech">[&nbsp;<?php echo $partOfSpeech; ?>&nbsp;]</p>
<div id="senseFrame">
<?php $definition = $sense->getSpace('definition');?>
<p class="definition"><?php echo $definition->get('en'); ?></p>
<div class="exampleFrame">
<h2>Examples</h2>
<?php $j = 0; $examples = $sense->getSpace('examples'); if ($examples) foreach($examples as $exampleGroup): ?>
<?php $example = $exampleGroup->getSpace('example'); $translation = $exampleGroup->getSpace('translation'); ?>
<div class="<? echo "r".$j%2; if ($j==0) echo " rFirst"; ?>">
<?php echo $example->get(LANG_Vernacular); ?>
</div>
</div>
<? $j++; endforeach; ?>
<? $i++; endforeach; ?>
</div>
</div>
</div>