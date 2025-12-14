<?php defined('_JEXEC') or die; ?>

<ul class="brSigContainer">
    <?php foreach($gallery as $photo): ?>
    <li class="brSigItem">
        <a href="<?php echo $photo->source; ?>" 
           class="brSigLink"
           data-fancybox="gallery<?php echo $this->galleryId; ?>">
           
           <img src="<?php echo $photo->thumb; ?>" 
                alt="" 
                width="<?php echo $this->thb_width; ?>"
                height="<?php echo $this->thb_height; ?>"
                loading="lazy">
        </a>
    </li>
    <?php endforeach; ?>
</ul>