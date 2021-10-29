<?php
/**
 * Brand SVG Please
 *
 * @version 1.0.1
 * @link https://github.com/nk-o/brand-svg-please
 * @package bsp
 */

if ( ! class_exists( 'Brand_SVG_Please' ) ) :
    /**
     * Brand_SVG_Please Class
     */
    class Brand_SVG_Please {
        /**
         * Get the SVG string for a given icon.
         *
         * @param String $name - brand name.
         * @param Array  $data - svg icon data.
         *
         * @return String
         */
        public static function get( $name, $data = array() ) {
            $brand = self::find_brand( $name );

            if ( $brand ) {
                return self::get_svg_by_path( $brand['svg_path'], $data );
            }

            return null;
        }

        /**
         * Print the SVG string for a given icon.
         *
         * @param String $name - icon name.
         * @param Array  $data - svg icon data.
         */
        public static function get_e( $name, $data = array() ) {
            if ( self::exists( $name ) ) {
                echo wp_kses( self::get( $name, $data ), self::kses() );
            }
        }

        /**
         * Get the SVG string for a given icon.
         *
         * @param String $name - brand name.
         *
         * @return String
         */
        public static function get_name( $name ) {
            $brand = self::find_brand( $name );

            if ( $brand ) {
                return $brand['name'];
            }

            return null;
        }

        /**
         * Check if SVG icon exists.
         *
         * @param String $name - brand name.
         *
         * @return Boolean
         */
        public static function exists( $name ) {
            return ! ! self::find_brand( $name );
        }

        /**
         * Data for SVG useful in wp_kses function.
         *
         * @return Array
         */
        public static function kses() {
            return array(
                'svg'   => array(
                    'class'           => true,
                    'aria-hidden'     => true,
                    'aria-labelledby' => true,
                    'role'            => true,
                    'focusable'       => true,
                    'xmlns'           => true,
                    'width'           => true,
                    'height'          => true,
                    'viewbox'         => true,
                ),
                'g'     => array(
                    'fill' => true,
                ),
                'title' => array(
                    'title' => true,
                ),
                'path'  => array(
                    'd'    => true,
                    'fill' => true,
                ),
            );
        }

        /**
         * Find brand data.
         *
         * @param String $name - brand name.
         *
         * @return Null|Array
         */
        private static function find_brand( $name ) {
            $result = null;
            $brands = self::get_all_brands();

            // Find by key.
            if ( isset( $brands[ $name ] ) ) {
                $result = $brands[ $name ];
            }

            // Find by alternative keys.
            if ( ! $result ) {
                foreach ( $brands as $brand ) {
                    if ( ! $result && isset( $brand['keys'] ) && in_array( $name, $brand['keys'], true ) ) {
                        $result = $brand;
                    }
                }
            }

            return $result;
        }

        /**
         * Get the SVG string for a given icon.
         *
         * @param String $path - icon path.
         * @param Array  $data - svg icon data.
         *
         * @return String
         */
        private static function get_svg_by_path( $path, $data = array() ) {
            $data = array_merge(
                array(
                    'size'  => 24,
                    'class' => 'bsp-icon',
                ),
                $data
            );

            if ( file_exists( $path ) ) {
                // We can't use file_get_contents in WordPress themes.
                ob_start();
                include $path;
                $svg = ob_get_clean();

                // Add extra attributes to SVG code.
                // translators: %1$s - classname.
                // translators: %2$d - size.
                $repl = sprintf( '<svg class="%1$s" width="%2$d" height="%2$d" aria-hidden="true" role="img" focusable="false" ', $data['class'], $data['size'] );
                $svg  = preg_replace( '/^<svg /', $repl, trim( $svg ) );

                return $svg;
            }

            return null;
        }

        /**
         * Get all available brands.
         *
         * @param Boolean $get_svg - get SVG and insert it inside array.
         * @param Array   $svg_data - svg data.
         *
         * @return Array
         */
        public static function get_all_brands( $get_svg = false, $svg_data = array() ) {
            $brands = array(
                '500px'                     => esc_html__( '500px', 'sociality' ),
                'accusoft'                  => esc_html__( 'Accusoft', 'sociality' ),
                'acquisitions-incorporated' => esc_html__( 'Acquisitions Incorporated', 'sociality' ),
                'adn'                       => esc_html__( 'ADN', 'sociality' ),
                'adobe'                     => esc_html__( 'Adobe', 'sociality' ),
                'adversal'                  => esc_html__( 'Adversal', 'sociality' ),
                'airbnb'                    => esc_html__( 'Airbnb', 'sociality' ),
                'algolia'                   => esc_html__( 'Algolia', 'sociality' ),
                'alipay'                    => esc_html__( 'Alipay', 'sociality' ),
                'amazon-pay'                => esc_html__( 'Amazon Pay', 'sociality' ),
                'amazon'                    => esc_html__( 'Amazon', 'sociality' ),
                'amilia'                    => esc_html__( 'Amilia', 'sociality' ),
                'android'                   => esc_html__( 'Android', 'sociality' ),
                'angellist'                 => esc_html__( 'AngelList', 'sociality' ),
                'angrycreative'             => esc_html__( 'Angry Creative', 'sociality' ),
                'angular'                   => esc_html__( 'Angular', 'sociality' ),
                'app-store'                 => esc_html__( 'App Store', 'sociality' ),
                'apper'                     => esc_html__( 'Apper', 'sociality' ),
                'apple-pay'                 => esc_html__( 'Apple Pay', 'sociality' ),
                'apple'                     => esc_html__( 'Apple', 'sociality' ),
                'artstation'                => esc_html__( 'ArtStation', 'sociality' ),
                'asymmetrik'                => esc_html__( 'Asymmetrik', 'sociality' ),
                'atlassian'                 => esc_html__( 'Atlassian', 'sociality' ),
                'audible'                   => esc_html__( 'Audible', 'sociality' ),
                'autoprefixer'              => esc_html__( 'Autoprefixer', 'sociality' ),
                'avianex'                   => esc_html__( 'Avianex', 'sociality' ),
                'aviato'                    => esc_html__( 'Aviato', 'sociality' ),
                'bandcamp'                  => esc_html__( 'Bandcamp', 'sociality' ),
                'battle-net'                => esc_html__( 'Battle.net', 'sociality' ),
                'behance'                   => esc_html__( 'Behance', 'sociality' ),
                'bimobject'                 => esc_html__( 'BIMobject', 'sociality' ),
                'bitbucket'                 => esc_html__( 'Bitbucket', 'sociality' ),
                'bitcoin'                   => esc_html__( 'Bitcoin', 'sociality' ),
                'bity'                      => esc_html__( 'Bity', 'sociality' ),
                'black-tie'                 => esc_html__( 'Black Tie', 'sociality' ),
                'blackberry'                => esc_html__( 'BlackBerry', 'sociality' ),
                'blogger'                   => esc_html__( 'Blogger', 'sociality' ),
                'bluetooth'                 => esc_html__( 'Bluetooth', 'sociality' ),
                'bootstrap'                 => esc_html__( 'Bootstrap', 'sociality' ),
                'btc'                       => esc_html__( 'BTC', 'sociality' ),
                'buffer'                    => esc_html__( 'Buffer', 'sociality' ),
                'buromobelexperte'          => esc_html__( 'Büromöbel Experte', 'sociality' ),
                'buy-n-large'               => esc_html__( 'Buy n Large', 'sociality' ),
                'buysellads'                => esc_html__( 'BuySellAds', 'sociality' ),
                'canadian-maple-leaf'       => esc_html__( 'Canadian Gold Maple Leaf', 'sociality' ),
                'cc-amazon-pay'             => esc_html__( 'Amazon Pay', 'sociality' ),
                'cc-amex'                   => esc_html__( 'Amex', 'sociality' ),
                'cc-apple-pay'              => esc_html__( 'Apple Pay', 'sociality' ),
                'cc-diners-club'            => esc_html__( 'Diners Club', 'sociality' ),
                'cc-discover'               => esc_html__( 'Discover', 'sociality' ),
                'cc-jcb'                    => esc_html__( 'JCB', 'sociality' ),
                'cc-mastercard'             => esc_html__( 'Mastercard', 'sociality' ),
                'cc-paypal'                 => esc_html__( 'PayPal', 'sociality' ),
                'cc-stripe'                 => esc_html__( 'Stripe', 'sociality' ),
                'cc-visa'                   => esc_html__( 'Visa', 'sociality' ),
                'centercode'                => esc_html__( 'Centercode', 'sociality' ),
                'centos'                    => esc_html__( 'CentOS', 'sociality' ),
                'chrome'                    => esc_html__( 'Chrome', 'sociality' ),
                'chromecast'                => esc_html__( 'Chromecast', 'sociality' ),
                'cloudscale'                => esc_html__( 'CloudScale', 'sociality' ),
                'cloudsmith'                => esc_html__( 'Cloudsmith', 'sociality' ),
                'cloudversify'              => esc_html__( 'Cloudversify', 'sociality' ),
                'codepen'                   => esc_html__( 'CodePen', 'sociality' ),
                'codiepie'                  => esc_html__( 'CodiePie', 'sociality' ),
                'confluence'                => esc_html__( 'Confluence', 'sociality' ),
                'connectdevelop'            => esc_html__( 'Connect Develop', 'sociality' ),
                'contao'                    => esc_html__( 'Contao', 'sociality' ),
                'cotton-bureau'             => esc_html__( 'Cotton Bureau', 'sociality' ),
                'cpanel'                    => esc_html__( 'cPanel', 'sociality' ),
                'critical-role'             => esc_html__( 'Critical Role', 'sociality' ),
                'css3'                      => esc_html__( 'CSS3', 'sociality' ),
                'cuttlefish'                => esc_html__( 'Cuttlefish', 'sociality' ),
                'd-and-d-beyond'            => esc_html__( 'D&D Beyond', 'sociality' ),
                'd-and-d'                   => esc_html__( 'D&D', 'sociality' ),
                'dailymotion'               => esc_html__( 'Dailymotion', 'sociality' ),
                'dashcube'                  => esc_html__( 'Dashcube', 'sociality' ),
                'delicious'                 => esc_html__( 'Delicious', 'sociality' ),
                'deploydog'                 => array(
                    'name' => esc_html__( 'deploy.dog', 'sociality' ),
                    'kays' => array( 'dd' ),
                ),
                'deskpro'                   => esc_html__( 'Deskpro', 'sociality' ),
                'dev'                       => esc_html__( 'Dev', 'sociality' ),
                'deviantart'                => esc_html__( 'DeviantArt', 'sociality' ),
                'dhl'                       => esc_html__( 'DHL', 'sociality' ),
                'diaspora'                  => esc_html__( 'Diaspora', 'sociality' ),
                'digg'                      => esc_html__( 'Digg', 'sociality' ),
                'digital-ocean'             => esc_html__( 'Digital Ocean', 'sociality' ),
                'discord'                   => esc_html__( 'Discord', 'sociality' ),
                'discourse'                 => esc_html__( 'Discourse', 'sociality' ),
                'dochub'                    => esc_html__( 'DocHub', 'sociality' ),
                'docker'                    => esc_html__( 'Docker', 'sociality' ),
                'draft2digital'             => esc_html__( 'Draft2Digital', 'sociality' ),
                'dribbble'                  => esc_html__( 'Dribbble', 'sociality' ),
                'dropbox'                   => esc_html__( 'Dropbox', 'sociality' ),
                'drupal'                    => esc_html__( 'Drupal', 'sociality' ),
                'dyalog'                    => esc_html__( 'Dyalog', 'sociality' ),
                'earlybirds'                => esc_html__( 'Earlybirds', 'sociality' ),
                'ebay'                      => esc_html__( 'eBay', 'sociality' ),
                'edge'                      => esc_html__( 'Edge', 'sociality' ),
                'elementor'                 => esc_html__( 'Elementor', 'sociality' ),
                'ello'                      => esc_html__( 'Ello', 'sociality' ),
                'ember'                     => esc_html__( 'Ember', 'sociality' ),
                'empire'                    => esc_html__( 'Empire', 'sociality' ),
                'envira'                    => esc_html__( 'Envira', 'sociality' ),
                'erlang'                    => esc_html__( 'Erlang', 'sociality' ),
                'ethereum'                  => esc_html__( 'Ethereum', 'sociality' ),
                'etsy'                      => esc_html__( 'Etsy', 'sociality' ),
                'evernote'                  => esc_html__( 'Evernote', 'sociality' ),
                'expeditedssl'              => esc_html__( 'ExpeditedSSL', 'sociality' ),
                'facebook-messenger'        => esc_html__( 'Facebook Messenger', 'sociality' ),
                'facebook'                  => esc_html__( 'Facebook', 'sociality' ),
                'fantasy-flight-games'      => esc_html__( 'Fantasy Flight Games', 'sociality' ),
                'fedex'                     => esc_html__( 'FedEx', 'sociality' ),
                'fedora'                    => esc_html__( 'Fedora', 'sociality' ),
                'figma'                     => esc_html__( 'Figma', 'sociality' ),
                'firefox-browser'           => esc_html__( 'Firefox Browser', 'sociality' ),
                'firefox'                   => esc_html__( 'Firefox', 'sociality' ),
                'first-order'               => esc_html__( 'First Order', 'sociality' ),
                'firstdraft'                => esc_html__( 'Firstdraft', 'sociality' ),
                'flickr'                    => esc_html__( 'Flickr', 'sociality' ),
                'flipboard'                 => esc_html__( 'Flipboard', 'sociality' ),
                'fly'                       => esc_html__( 'Fly', 'sociality' ),
                'font-awesome'              => esc_html__( 'Font Awesome', 'sociality' ),
                'fonticons'                 => esc_html__( 'Fonticons', 'sociality' ),
                'fort-awesome'              => esc_html__( 'Fort Awesome', 'sociality' ),
                'forumbee'                  => esc_html__( 'Forumbee', 'sociality' ),
                'foursquare'                => esc_html__( 'Foursquare', 'sociality' ),
                'free-code-camp'            => esc_html__( 'freeCodeCamp', 'sociality' ),
                'freebsd'                   => esc_html__( 'FreeBSD', 'sociality' ),
                'fulcrum'                   => esc_html__( 'Fulcrum', 'sociality' ),
                'galactic-republic'         => esc_html__( 'Galactic Republic', 'sociality' ),
                'galactic-senate'           => esc_html__( 'Galactic Senate', 'sociality' ),
                'get-pocket'                => array(
                    'name' => esc_html__( 'Pocket', 'sociality' ),
                    'keys' => array( 'pocket' ),
                ),
                'gg'                        => esc_html__( 'GG', 'sociality' ),
                'git'                       => esc_html__( 'Git', 'sociality' ),
                'github'                    => esc_html__( 'GitHub', 'sociality' ),
                'gitkraken'                 => esc_html__( 'GitKraken', 'sociality' ),
                'gitlab'                    => esc_html__( 'GitLab', 'sociality' ),
                'gitter'                    => esc_html__( 'Gitter', 'sociality' ),
                'glide'                     => esc_html__( 'Glide', 'sociality' ),
                'gofore'                    => esc_html__( 'Gofore', 'sociality' ),
                'goodreads'                 => esc_html__( 'Goodreads', 'sociality' ),
                'google-drive'              => esc_html__( 'Google Drive', 'sociality' ),
                'google-play'               => esc_html__( 'Google Play', 'sociality' ),
                'google-plus'               => esc_html__( 'Google Plus', 'sociality' ),
                'google-wallet'             => esc_html__( 'Google Wallet', 'sociality' ),
                'google'                    => esc_html__( 'Google', 'sociality' ),
                'gratipay'                  => esc_html__( 'Gratipay', 'sociality' ),
                'grav'                      => esc_html__( 'Grav', 'sociality' ),
                'gripfire'                  => esc_html__( 'Gripfire', 'sociality' ),
                'grunt'                     => esc_html__( 'Grunt', 'sociality' ),
                'gulp'                      => esc_html__( 'Gulp', 'sociality' ),
                'hacker-news'               => esc_html__( 'Hacker News', 'sociality' ),
                'hackerrank'                => esc_html__( 'HackerRank', 'sociality' ),
                'hips'                      => esc_html__( 'HIPS', 'sociality' ),
                'hire-a-helper'             => esc_html__( 'HireAHelper', 'sociality' ),
                'hornbill'                  => esc_html__( 'Hornbill', 'sociality' ),
                'hotjar'                    => esc_html__( 'Hotjar', 'sociality' ),
                'houzz'                     => esc_html__( 'Houzz', 'sociality' ),
                'html5'                     => esc_html__( 'HTML5', 'sociality' ),
                'hubspot'                   => esc_html__( 'HubSpot', 'sociality' ),
                'ideal'                     => esc_html__( 'iDEAL', 'sociality' ),
                'imdb'                      => esc_html__( 'IMDb', 'sociality' ),
                'instagram'                 => esc_html__( 'Instagram', 'sociality' ),
                'intercom'                  => esc_html__( 'Intercom', 'sociality' ),
                'internet-explorer'         => array(
                    'name' => esc_html__( 'Internet Explorer', 'sociality' ),
                    'keys' => array( 'ie' ),
                ),
                'invision'                  => esc_html__( 'InVision', 'sociality' ),
                'ioxhost'                   => esc_html__( 'IoxHost', 'sociality' ),
                'itch-io'                   => esc_html__( 'itch.io', 'sociality' ),
                'itunes'                    => esc_html__( 'iTunes', 'sociality' ),
                'java'                      => esc_html__( 'Java', 'sociality' ),
                'jedi-order'                => esc_html__( 'Jedi Order', 'sociality' ),
                'jenkins'                   => esc_html__( 'Jenkins', 'sociality' ),
                'jira'                      => esc_html__( 'Jira', 'sociality' ),
                'joget'                     => esc_html__( 'Joget', 'sociality' ),
                'joomla'                    => esc_html__( 'Joomla', 'sociality' ),
                'js'                        => array(
                    'name' => esc_html__( 'JS', 'sociality' ),
                    'keys' => array( 'javascript' ),
                ),
                'jsfiddle'                  => esc_html__( 'JSFiddle', 'sociality' ),
                'kaggle'                    => esc_html__( 'Kaggle', 'sociality' ),
                'keybase'                   => esc_html__( 'Keybase', 'sociality' ),
                'keycdn'                    => esc_html__( 'KeyCDN', 'sociality' ),
                'kickstarter'               => esc_html__( 'Kickstarter', 'sociality' ),
                'korvue'                    => esc_html__( 'Korvue', 'sociality' ),
                'laravel'                   => esc_html__( 'Laravel', 'sociality' ),
                'lastfm'                    => esc_html__( 'Last.fm', 'sociality' ),
                'leanpub'                   => esc_html__( 'Leanpub', 'sociality' ),
                'less'                      => esc_html__( 'Less', 'sociality' ),
                'line'                      => esc_html__( 'Line', 'sociality' ),
                'linkedin'                  => esc_html__( 'LinkedIn', 'sociality' ),
                'linode'                    => esc_html__( 'Linode', 'sociality' ),
                'linux'                     => esc_html__( 'Linux', 'sociality' ),
                'lyft'                      => esc_html__( 'Lyft', 'sociality' ),
                'magento'                   => esc_html__( 'Magento', 'sociality' ),
                'mailchimp'                 => esc_html__( 'Mailchimp', 'sociality' ),
                'mandalorian'               => esc_html__( 'Mandalorian', 'sociality' ),
                'markdown'                  => array(
                    'name' => esc_html__( 'Markdown', 'sociality' ),
                    'keys' => array( 'md' ),
                ),
                'mastodon'                  => esc_html__( 'Mastodon', 'sociality' ),
                'maxcdn'                    => esc_html__( 'MaxCDN', 'sociality' ),
                'mdb'                       => esc_html__( 'MDB', 'sociality' ),
                'medapps'                   => esc_html__( 'MedApps', 'sociality' ),
                'medium'                    => esc_html__( 'Medium', 'sociality' ),
                'medrt'                     => esc_html__( 'Medrt', 'sociality' ),
                'meetup'                    => esc_html__( 'Meetup', 'sociality' ),
                'megaport'                  => esc_html__( 'Megaport', 'sociality' ),
                'mendeley'                  => esc_html__( 'Mendeley', 'sociality' ),
                'microblog'                 => esc_html__( 'Micro.blog', 'sociality' ),
                'microsoft'                 => esc_html__( 'Microsoft', 'sociality' ),
                'mix'                       => esc_html__( 'Mix', 'sociality' ),
                'mixcloud'                  => esc_html__( 'Mixcloud', 'sociality' ),
                'mixer'                     => esc_html__( 'Mixer', 'sociality' ),
                'mizuni'                    => esc_html__( 'Mizuni', 'sociality' ),
                'modx'                      => esc_html__( 'MODX', 'sociality' ),
                'monero'                    => esc_html__( 'Monero', 'sociality' ),
                'napster'                   => esc_html__( 'Mapster', 'sociality' ),
                'neos'                      => esc_html__( 'Neos', 'sociality' ),
                'nimblr'                    => esc_html__( 'Nimblr', 'sociality' ),
                'node-js'                   => esc_html__( 'Node.js', 'sociality' ),
                'node'                      => esc_html__( 'Node', 'sociality' ),
                'npm'                       => esc_html__( 'npm', 'sociality' ),
                'ns8'                       => esc_html__( 'NS8', 'sociality' ),
                'nutritionix'               => esc_html__( 'Nutritionix', 'sociality' ),
                'odnoklassniki'             => array(
                    'name' => esc_html__( 'Odnoklassniki', 'sociality' ),
                    'keys' => array( 'ok' ),
                ),
                'old-republic'              => esc_html__( 'Old Republic', 'sociality' ),
                'opencart'                  => esc_html__( 'OpenCart', 'sociality' ),
                'openid'                    => esc_html__( 'OpenID', 'sociality' ),
                'opera'                     => esc_html__( 'Opera', 'sociality' ),
                'optin-monster'             => esc_html__( 'OptinMonster', 'sociality' ),
                'orcid'                     => esc_html__( 'ORCID', 'sociality' ),
                'osi'                       => esc_html__( 'OSI', 'sociality' ),
                'page4'                     => esc_html__( 'PAGE4', 'sociality' ),
                'pagelines'                 => esc_html__( 'PageLines', 'sociality' ),
                'palfed'                    => esc_html__( 'PalFed', 'sociality' ),
                'patreon'                   => esc_html__( 'Patreon', 'sociality' ),
                'paypal'                    => esc_html__( 'PayPal', 'sociality' ),
                'penny-arcade'              => esc_html__( 'Penny Arcade', 'sociality' ),
                'periscope'                 => esc_html__( 'Periscope', 'sociality' ),
                'phabricator'               => esc_html__( 'Phabricator', 'sociality' ),
                'phoenix-framework'         => esc_html__( 'Phoenix Framework', 'sociality' ),
                'phoenix-squadron'          => esc_html__( 'Phoenix Squadron', 'sociality' ),
                'php'                       => esc_html__( 'PHP', 'sociality' ),
                'pinterest'                 => esc_html__( 'Pinterest', 'sociality' ),
                'playstation'               => array(
                    'name' => esc_html__( 'PlayStation', 'sociality' ),
                    'keys' => array( 'ps' ),
                ),
                'product-hunt'              => esc_html__( 'Product Hunt', 'sociality' ),
                'pushed'                    => esc_html__( 'Pushed', 'sociality' ),
                'python'                    => esc_html__( 'Python', 'sociality' ),
                'qq'                        => array(
                    'name' => esc_html__( 'QQ', 'sociality' ),
                    'keys' => array( 'tencent-qq' ),
                ),
                'quinscape'                 => esc_html__( 'QuinScape', 'sociality' ),
                'quora'                     => esc_html__( 'Quora', 'sociality' ),
                'r-project'                 => esc_html__( 'R', 'sociality' ),
                'raspberry-pi'              => esc_html__( 'Raspberry Pi', 'sociality' ),
                'ravelry'                   => esc_html__( 'Ravelry', 'sociality' ),
                'react'                     => esc_html__( 'React', 'sociality' ),
                'reacteurope'               => esc_html__( 'ReactEurope', 'sociality' ),
                'readme'                    => esc_html__( 'ReadMe', 'sociality' ),
                'rebel'                     => esc_html__( 'Rebel', 'sociality' ),
                'red-river'                 => esc_html__( 'Red River', 'sociality' ),
                'reddit'                    => esc_html__( 'reddit', 'sociality' ),
                'redhat'                    => esc_html__( 'Red Hat', 'sociality' ),
                'renren'                    => esc_html__( 'Renren', 'sociality' ),
                'replyd'                    => esc_html__( 'Replyd', 'sociality' ),
                'researchgate'              => esc_html__( 'ResearchGate', 'sociality' ),
                'resolving'                 => esc_html__( 'Resolving', 'sociality' ),
                'rev'                       => esc_html__( 'Rev', 'sociality' ),
                'rocketchat'                => esc_html__( 'Rocket.Chat', 'sociality' ),
                'rockrms'                   => esc_html__( 'Rock RMS', 'sociality' ),
                'safari'                    => esc_html__( 'Safari', 'sociality' ),
                'salesforce'                => esc_html__( 'Salesforce', 'sociality' ),
                'sass'                      => esc_html__( 'Sass', 'sociality' ),
                'schlix'                    => esc_html__( 'SCHLIX', 'sociality' ),
                'scribd'                    => esc_html__( 'Scribd', 'sociality' ),
                'searchengin'               => esc_html__( 'Searchengin', 'sociality' ),
                'sellcast'                  => esc_html__( 'SellCast', 'sociality' ),
                'sellsy'                    => esc_html__( 'Sellsy', 'sociality' ),
                'servicestack'              => esc_html__( 'ServiceStack', 'sociality' ),
                'shirtsinbulk'              => esc_html__( 'Shirts In Bulk', 'sociality' ),
                'shopify'                   => esc_html__( 'Shopify', 'sociality' ),
                'shopware'                  => esc_html__( 'Shopware', 'sociality' ),
                'simplybuilt'               => esc_html__( 'SimplyBuilt', 'sociality' ),
                'sistrix'                   => esc_html__( 'SISTRIX', 'sociality' ),
                'sith'                      => esc_html__( 'Sith', 'sociality' ),
                'sketch'                    => esc_html__( 'Sketch', 'sociality' ),
                'skyatlas'                  => esc_html__( 'SkyAtlas', 'sociality' ),
                'skype'                     => esc_html__( 'Skype', 'sociality' ),
                'slack'                     => esc_html__( 'Slack', 'sociality' ),
                'slideshare'                => esc_html__( 'SlideShare', 'sociality' ),
                'snapchat'                  => esc_html__( 'Snapchat', 'sociality' ),
                'soundcloud'                => esc_html__( 'SoundCloud', 'sociality' ),
                'sourcetree'                => esc_html__( 'Sourcetree', 'sociality' ),
                'speakap'                   => esc_html__( 'Speakap', 'sociality' ),
                'speaker-deck'              => esc_html__( 'Speaker Deck', 'sociality' ),
                'spotify'                   => esc_html__( 'Spotify', 'sociality' ),
                'squarespace'               => esc_html__( 'Squarespace', 'sociality' ),
                'stack-exchange'            => esc_html__( 'Stack Exchange', 'sociality' ),
                'stack-overflow'            => esc_html__( 'Stack Overflow', 'sociality' ),
                'stackpath'                 => esc_html__( 'StackPath', 'sociality' ),
                'staylinked'                => esc_html__( 'StayLinked', 'sociality' ),
                'steam'                     => esc_html__( 'Steam', 'sociality' ),
                'sticker-mule'              => esc_html__( 'Sticker Mule', 'sociality' ),
                'strava'                    => esc_html__( 'Strava', 'sociality' ),
                'stripe'                    => esc_html__( 'Stripe', 'sociality' ),
                'studiovinari'              => esc_html__( 'Studio Vinari', 'sociality' ),
                'stumbleupon'               => esc_html__( 'StumbleUpon', 'sociality' ),
                'superpowers'               => esc_html__( 'Superpowers', 'sociality' ),
                'supple'                    => esc_html__( 'Supple', 'sociality' ),
                'suse'                      => esc_html__( 'SuSE', 'sociality' ),
                'swift'                     => esc_html__( 'Swift', 'sociality' ),
                'symfony'                   => esc_html__( 'Symfony', 'sociality' ),
                'teamspeak'                 => esc_html__( 'SeamSpeak', 'sociality' ),
                'telegram'                  => esc_html__( 'Telegram', 'sociality' ),
                'tencent-weibo'             => esc_html__( 'Tencent Weibo', 'sociality' ),
                'the-red-yeti'              => esc_html__( 'The Red Yeti', 'sociality' ),
                'themeisle'                 => esc_html__( 'Themeisle', 'sociality' ),
                'think-peaks'               => esc_html__( 'ThinkPeaks', 'sociality' ),
                'tiktok'                    => esc_html__( 'TikTok', 'sociality' ),
                'trade-federation'          => esc_html__( 'Trade Federation', 'sociality' ),
                'trello'                    => esc_html__( 'Trello', 'sociality' ),
                'tripadvisor'               => esc_html__( 'Tripadvisor', 'sociality' ),
                'tumblr'                    => esc_html__( 'Tumblr', 'sociality' ),
                'twitch'                    => esc_html__( 'Twitch', 'sociality' ),
                'twitter'                   => esc_html__( 'Twitter', 'sociality' ),
                'typo3'                     => esc_html__( 'TYPO3', 'sociality' ),
                'uber'                      => esc_html__( 'Uber', 'sociality' ),
                'ubuntu'                    => esc_html__( 'Ubuntu', 'sociality' ),
                'uikit'                     => esc_html__( 'UIkit', 'sociality' ),
                'umbraco'                   => esc_html__( 'Umbraco', 'sociality' ),
                'uniregistry'               => esc_html__( 'Uniregistry', 'sociality' ),
                'unity'                     => esc_html__( 'Unity', 'sociality' ),
                'untappd'                   => esc_html__( 'Untappd', 'sociality' ),
                'ups'                       => esc_html__( 'UPS', 'sociality' ),
                'usps'                      => esc_html__( 'USPS', 'sociality' ),
                'ussunnah'                  => esc_html__( 'us-Sunnah', 'sociality' ),
                'vaadin'                    => esc_html__( 'Vaadin', 'sociality' ),
                'viacoin'                   => esc_html__( 'Viacoin', 'sociality' ),
                'viadeo'                    => esc_html__( 'Viadeo', 'sociality' ),
                'viber'                     => esc_html__( 'Viber', 'sociality' ),
                'vimeo'                     => esc_html__( 'Vimeo', 'sociality' ),
                'vine'                      => esc_html__( 'Vine', 'sociality' ),
                'vk'                        => array(
                    'name' => esc_html__( 'VK', 'sociality' ),
                    'keys' => array( 'vkontakte' ),
                ),
                'vnv'                       => esc_html__( 'VNV', 'sociality' ),
                'vuejs'                     => esc_html__( 'Vue.js', 'sociality' ),
                'waze'                      => esc_html__( 'Waze', 'sociality' ),
                'wechat'                    => array(
                    'name' => esc_html__( 'WeChat', 'sociality' ),
                    'keys' => array( 'weixin' ),
                ),
                'weebly'                    => esc_html__( 'Weebly', 'sociality' ),
                'weibo'                     => array(
                    'name' => esc_html__( 'Sina Weibo', 'sociality' ),
                    'keys' => array( 'sina-weibo' ),
                ),
                'weixin'                    => esc_html__( 'Weixin', 'sociality' ),
                'whatsapp'                  => esc_html__( 'WhatsApp', 'sociality' ),
                'whmcs'                     => esc_html__( 'WHMCS', 'sociality' ),
                'wikipedia'                 => esc_html__( 'Wikipedia', 'sociality' ),
                'windows'                   => esc_html__( 'Windows', 'sociality' ),
                'wix'                       => esc_html__( 'WIX', 'sociality' ),
                'wizards-of-the-coast'      => esc_html__( 'Wizards of the Coast', 'sociality' ),
                'wolf-pack-battalion'       => esc_html__( 'Wolf Pack Battalion', 'sociality' ),
                'wordpress'                 => esc_html__( 'WordPress', 'sociality' ),
                'wpbeginner'                => esc_html__( 'WPBeginner', 'sociality' ),
                'wpexplorer'                => esc_html__( 'WPExplorer', 'sociality' ),
                'wpforms'                   => esc_html__( 'WPForms', 'sociality' ),
                'wpressr'                   => esc_html__( 'WPressr', 'sociality' ),
                'xbox'                      => esc_html__( 'Xbox', 'sociality' ),
                'xing'                      => esc_html__( 'XING', 'sociality' ),
                'y-combinator'              => esc_html__( 'YCombinator', 'sociality' ),
                'yahoo'                     => esc_html__( 'Yahoo', 'sociality' ),
                'yammer'                    => esc_html__( 'Yammer', 'sociality' ),
                'yandex'                    => esc_html__( 'Yandex', 'sociality' ),
                'yarn'                      => esc_html__( 'Yarn', 'sociality' ),
                'yelp'                      => esc_html__( 'Yelp', 'sociality' ),
                'yoast'                     => esc_html__( 'Yoast', 'sociality' ),
                'youtube'                   => esc_html__( 'Youtube', 'sociality' ),
                'zhihu'                     => esc_html__( 'Zhihu', 'sociality' ),
            );

            $result    = array();
            $base_path = __DIR__ . '/svg/';

            // Prepare SVG paths.
            foreach ( $brands as $k => $data ) {
                $svg_path = $base_path . $k . '.svg';

                if ( file_exists( $svg_path ) ) {
                    $result[ $k ] = array_merge(
                        is_array( $data ) ? $data : array(
                            'name' => $data,
                        ),
                        $get_svg ? array(
                            'svg' => self::get_svg_by_path( $svg_path, $svg_data ),
                        ) : array(),
                        array(
                            'svg_path' => $base_path . $k . '.svg',
                        )
                    );
                }
            }

            return $result;
        }
    }
endif;
