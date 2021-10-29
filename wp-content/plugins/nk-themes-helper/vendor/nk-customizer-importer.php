<?php

class NKTH_Customizer_Import extends Customizer_Import {
    // we need to check if this image already exist in blog
    public function _sideload_image( $file ) {
        $exists = nkth()->demo_importer()->wp_import->post_exists_pub(array(
            'guid' => $file
        ));

        if ($exists) {
            $data = new stdClass();

            // Build the object to return.
            $meta					= wp_get_attachment_metadata( $exists );
            $data->attachment_id	= $exists;
            $data->url				= wp_get_attachment_url( $exists );
            $data->thumbnail_url	= wp_get_attachment_thumb_url( $exists );
            $data->height			= $meta['height'];
            $data->width			= $meta['width'];

            return $data;
        }

        return parent::_sideload_image($file);
    }
}
