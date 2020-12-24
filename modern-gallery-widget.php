<?php

class Elementor_Modern_Gallery_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'modern-gallery';
    }

    public function get_title()
    {
        return __('Modern Gallery', 'elementor-modern-gallery-extension');
    }

    public function get_icon()
    {
        return 'fa fa-code';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-modern-gallery-extension'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery',
            [
                'label' => __('Images to Display', 'elementor-modern-gallery-extension'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $this->add_control(
            'column_count',
            [
                'label' => __('Column Count', 'elementor-modern-gallery-extension'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['#'],
                'range' => [
                    '#' => [
                        'min' => 2,
                        'max' => 5,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '#',
                    'size' => 4,
                ],
            ]
        );

        $this->add_control(
            'shuffle_images',
            [
                'label' => __('Shuffle Images?', 'elementor-modern-gallery-extension'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Shuffle', 'elementor-modern-gallery-extension'),
                'label_off' => __('Don\'t shuffle', 'elementor-modern-gallery-extension'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        echo '<style>';
        echo file_get_contents(__DIR__ . '/modern-gallery-widget.css');
        echo '</style>';

        $settings = $this->get_settings_for_display();
        $column_count = $settings['column_count']['size'];


        $images = $settings['gallery'];
        if ($settings['shuffle_images'] === 'yes') {
            shuffle($images);
        }

        echo '<div class="modern-gallery columns-' . $column_count . '">';
        foreach ($images as $image) {
            $link_html = '<a href="' . $image['url'] . '">';
            $link_html = $this->add_lightbox_data_to_image_link($link_html, $image['id']);
            echo '<div class="modern-gallery-image">';
            echo $link_html;
            echo '<img src="' . $image['url'] . '" />';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
    }

    protected function _content_template()
    {
    }
}
