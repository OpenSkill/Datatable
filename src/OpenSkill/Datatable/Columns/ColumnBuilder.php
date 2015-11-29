<?php

namespace OpenSkill\Datatable\Columns;


use OpenSkill\Datatable\Composers\ColumnComposer;

class ColumnBuilder extends ColumnConfigurationBuilder
{
    /**
     * @var ColumnComposer
     */
    private $composer;

    /**
     * ColumnBuilder constructor.
     *
     * @param ColumnComposer $composer
     */
    public function __construct(ColumnComposer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * Will create the final ColumnConfiguration
     *
     * @return ColumnComposer
     */
    public function build()
    {
        $configuration = parent::build();
        return $this->composer->configure($configuration);
    }

    /**
     * Will create a new builder for a ColumnConfigurationBuilder.
     *
     * @param ColumnComposer $composer
     * @return ColumnBuilder
     */
    public static function create(ColumnComposer $composer)
    {
        return new ColumnBuilder($composer);
    }


}