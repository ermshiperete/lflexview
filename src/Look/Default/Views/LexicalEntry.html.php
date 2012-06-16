<div><?php $lexicalUnit = $LexicalEntry->getSpace('lexicalUnit'); ?>
<h2>Some Lex Entry</h2>
<p>guid <?php echo $LexicalEntry->getID(); ?></p>
<p>th <?php echo $lexicalUnit->get(LANG_Vernacular);?></p>
<p>th-fonipa <?php echo $lexicalUnit->get(LANG_IPA);?></p>
<?php $i = 1; $senses = $LexicalEntry->getSpace('senses'); if ($senses) foreach($senses as $sense): ?>
<?php $definition = $sense->getSpace('definition');?>
<p>definition <?php echo $definition->get('en'); ?>
<?php $partOfSpeech = $sense->get('partOfSpeech');?>
<p>part of speech <?php echo $partOfSpeech; ?>
<?php $j = 1; $examples = $senses->getSpace('examples'); if ($examples) foreach($examples as $exampleGroup): ?>
<?php $example = $exampleGroup->getSpace('example'); $translation = $exampleGroup->getSpace('translation'); ?>
<p>example th <?php echo $example->get(LANG_Vernacular);?></p>
<p>example th-fonipa <?php echo $example->get(LANG_IPA);?></p>
<p>translation en <?php echo $translation->get(LANG_Other);?></p>
<? $j++; endforeach; ?>
<? $i++; endforeach; ?>
</div>
