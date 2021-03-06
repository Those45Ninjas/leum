<?php

/**
 * The page header widget shows the header of a page.
 * It supports menu widgets.
 */
class Page_Header implements IWidget
{
	public $title;
	public $subtitle;
	public $menu;
	public $content;
	public $class;

	function __construct($arguments)
	{
		// Get the title.
		if(isset($arguments['title']))
			$this->title = $arguments['title'];

		// Get the subtitle if it exists.
		if(isset($arguments['subtitle']))
			$this->subtitle = $arguments['subtitle'];

		// Get the menu if it exists.
		if(isset($arguments['menu']) && $arguments['menu'] instanceof IWidget)
			$this->menu = $arguments['menu'];

		if(isset($arguments['content']))
			$this->content = $arguments['content'];

		// Get the class if it exists. Add a space to the front if needed.
		if(isset($arguments['class']))
			$this->class = ' ' . $arguments['class'];
	}

	public function Show()
	{
		// Create the HTML for the page header.
		?>
		<div class="header<?=$this->class?>">
			<div class="content">
				<h1><?=$this->title?></h1>
				<h2><?=$this->subtitle?></h2>
				<?php
					if(isset($this->menu))
						$this->menu->Show();

					if(isset($this->content))
					{
						if($this->content instanceof IWidget)
							$this->content->Show();

						elseif(is_callable($this->content))
						{
							$fun = $this->content;
							$fun();
						}

						else
							echo $this->content;
					}

				?>
			</div>
		</div>
		<?php
	}
}

?>