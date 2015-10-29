<?php

namespace OpenSkill\Datatable\Columns;


use OpenSkill\Datatable\Composers\DTDataComposer;

class ColumnBuilder extends ColumnConfigurationBuilder
{
    /**
     * @var DTDataComposer
     */
    private $composer;

    /**
     * ColumnBuilder constructor.
     *
     * @param DTDataComposer $composer
     */
    public function __construct(DTDataComposer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * Will create the final ColumnConfiguration
     *
     * @return DTDataComposer
     */
    public function build()
    {
        $configuration = parent::build();
        return $this->composer->configure($configuration);
    }

    /**
     * Will create a new builder for a ColumnConfigurationBuilder.
     *
     * @param DTDataComposer $composer
     * @return ColumnBuilder
     */
    public static function create(DTDataComposer $composer)
    {
        return new ColumnBuilder($composer);
    }


}