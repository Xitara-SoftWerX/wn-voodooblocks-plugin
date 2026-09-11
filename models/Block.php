<?php

namespace Xitara\VoodooBlocks\Models;

use Model;

/**
 * Block Model
 *
 * @property int                                            $id
 * @property string                                         $heading
 * @property string                                         $subheading
 * @property int|null                                       $blocklist_id
 * @property string                                         $width
 * @property string                                         $height
 * @property int                                            $is_active
 * @property int                                            $is_raw
 * @property int                                            $is_heading
 * @property int                                            $is_box
 * @property int                                            $is_scrollbar
 * @property string                                         $excerpt
 * @property string|null                                    $content
 * @property string|null                                    $buttons_above
 * @property string|null                                    $buttons
 * @property int                                            $is_time_control
 * @property string|null                                    $start_at
 * @property string|null                                    $end_at
 * @property string|null                                    $images
 * @property int                                            $is_image_text
 * @property int                                            $is_slider
 * @property int                                            $is_lightbox
 * @property string|null                                    $slider
 * @property string|null                                    $lightbox
 * @property string|null                                    $modules
 * @property \Illuminate\Support\Carbon|null                $created_at
 * @property \Illuminate\Support\Carbon|null                $updated_at
 * @method static \Winter\Storm\Database\Collection<int, static> all($columns = ['*'])
 * @method static \Winter\Storm\Database\Collection<int, static> get($columns = ['*'])
 * @method static \Winter\Storm\Database\Builder|Block           lists(string $column, string $key = null)
 * @method static \Winter\Storm\Database\Builder|Block           newModelQuery()
 * @method static \Winter\Storm\Database\Builder|Block           newQuery()
 * @method static \Winter\Storm\Database\Builder|Block           orSearchWhere(string $term, string $columns = [], string $mode = 'all')
 * @method static \Winter\Storm\Database\Builder|Block           query()
 * @method static \Winter\Storm\Database\Builder|Block           searchWhere(string $term, string $columns = [], string $mode = 'all')
 * @method static \Winter\Storm\Database\Builder|Block           whereBlocklistId($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereButtons($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereButtonsAbove($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereContent($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereCreatedAt($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereEndAt($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereExcerpt($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereHeading($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereHeight($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereId($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereImages($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsActive($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsBox($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsHeading($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsImageText($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsLightbox($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsRaw($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsScrollbar($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsSlider($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereIsTimeControl($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereLightbox($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereModules($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereSlider($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereStartAt($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereSubheading($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereUpdatedAt($value)
 * @method static \Winter\Storm\Database\Builder|Block           whereWidth($value)
 * @mixin \Eloquent
 */
class Block extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'xitara_voodooblocks_blocks';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    // protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    // protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'buttons_above',
        'buttons',
        'images',
        'slider',
        'lightbox',
        'modules',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    // protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    // protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    // public $hasOne = [];
    // public $hasMany = [];
    // public $hasOneThrough = [];
    // public $hasManyThrough = [];
    public $belongsTo = [
        'blocklist' => Blocklist::class,
    ];
    // public $belongsToMany = [];
    // public $morphTo = [];
    // public $morphOne = [];
    // public $morphMany = [];
    // public $attachOne = [];
    // public $attachMany = [];

    public function getDropdownOptions($field, $value, $form)
    {
        // \Log::debug($field);
        // \Log::debug(post('_repeater_group'));
        // \Log::debug(post('group'));
        // \Log::debug($value);

        if (isset($form->_group)) {
            // \Log::debug($form->_group);
            $group = $form->_group;
        } else {
            $group = post('_repeater_group');
        }

        $namespace = '\\' . str_replace('-', '\\', $group);
        $method = 'get' . ucfirst(camel_case($field)) . 'Options';
        // \Log::debug($method);
        // \Log::debug($namespace);

        // return \LaFetEnt\BlockExtender\Modules\VoodooTest\VoodooTest::getTextTestOptions($value, $form);

        if (method_exists($namespace, $method)) {
            return $namespace::$method($value, $form);
        }

        // if (PluginManager::instance()->exists('Xitara.VoodooBlocks') === true) {
        // }

        // if ($fieldName == 'status') {
        // return ['all' => 'All'];
        // } else {
        return ['' => '-- none --'];
        // }
    }
}
