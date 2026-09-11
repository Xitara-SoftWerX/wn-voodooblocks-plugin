<?php

namespace Xitara\VoodooBlocks\Models;

use Model;

/**
 * Blocklist Model
 *
 * @property int                                            $id
 * @property string|null                                    $heading
 * @property string|null                                    $subheading
 * @property int|null                                       $is_heading
 * @property int|null                                       $is_active
 * @property int|null                                       $is_default_css
 * @property int|null                                       $is_raw
 * @property \Illuminate\Support\Carbon|null                $created_at
 * @property \Illuminate\Support\Carbon|null                $updated_at
 * @method static \Winter\Storm\Database\Collection<int, static> all($columns = ['*'])
 * @method static \Winter\Storm\Database\Collection<int, static> get($columns = ['*'])
 * @method static \Winter\Storm\Database\Builder|Blocklist       lists(string $column, string $key = null)
 * @method static \Winter\Storm\Database\Builder|Blocklist       newModelQuery()
 * @method static \Winter\Storm\Database\Builder|Blocklist       newQuery()
 * @method static \Winter\Storm\Database\Builder|Blocklist       orSearchWhere(string $term, string $columns = [], string $mode = 'all')
 * @method static \Winter\Storm\Database\Builder|Blocklist       query()
 * @method static \Winter\Storm\Database\Builder|Blocklist       searchWhere(string $term, string $columns = [], string $mode = 'all')
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereCreatedAt($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereHeading($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereId($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereIsActive($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereIsDefaultCss($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereIsHeading($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereIsRaw($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereSubheading($value)
 * @method static \Winter\Storm\Database\Builder|Blocklist       whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Blocklist extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'xitara_voodooblocks_blocklists';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

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
    public $hasOne = [];
    public $hasMany = [
        'blocks' => Block::class,
    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    // public function beforeSave()
    // {
    //     $blocks = $this->blocks;

    //     foreach ($blocks as $block) {
    //         $block['excerpt'] = trim($block['excerpt']);
    //         $block['content'] = trim($block['content']);

    //         $blocks_[] = $block;
    //     }

    //     $this->blocks = $blocks_;
    // }

    // public function getDropdownOptions($fieldName, $value, $formData)
    // {
    //     if (input('_repeater_group', null) != null) {
    //         $group = input('_repeater_group');
    //     }

    //     if (isset($formData->_group)) {
    //         $group = $formData->_group;
    //     }

    //     if (isset($group)) {
    //         $class = '\\Xitara\\DynamicContentModules\\Classes\\';
    //         $class .= camel_case($group);
    //         $method = 'get' . ucfirst($fieldName) . 'Options';

    //         if (method_exists($class, $method)) {
    //             return call_user_func([$class, $method]);
    //         }
    //     }

    //     return self::getGroupedTextOptions($group);
    // }

    // public static function getGroupedTextOptions($group)
    // {
    //     if ($group === null) {
    //         return Text::orderBy('name', 'asc')->lists('name', 'id');
    //     }

    //     $texts = Text::orderBy('name', 'asc')->get();

    //     if ($texts === null) {
    //         return [];
    //     }

    //     $group = Group::where('slug', str_slug($group))
    //         ->orWhere('name', $group)
    //         ->first();

    //     if ($group === null) {
    //         return Text::orderBy('name', 'asc')->lists('name', 'id');
    //     }

    //     $textList = [];
    //     foreach ($texts as $text) {
    //         foreach ($text->groups as $group_) {
    //             if ($group->id == $group_->id) {
    //                 $textList[$text->id] = $text->name;
    //             }
    //         }
    //     }

    //     if (empty($textList)) {
    //         return Text::orderBy('name', 'asc')->lists('name', 'id');
    //     }

    //     return $textList;
    // }
}
