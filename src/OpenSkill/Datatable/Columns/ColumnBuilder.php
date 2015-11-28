<?php

namespace OpenSkill\Datatable\Columns;


use OpenSkill\Datatable\Composers\DataComposer;

class ColumnBuilder extends ColumnConfigurationBuilder
{
    /**
     * @var DataComposer
     */
    private $composer;

    /**
     * ColumnBuilder constructor.
     *
     * @param DataComposer $composer
     */
    public function __construct(DataComposer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * Will create the final ColumnConfiguration
     *
     * @return DataComposer
     */
    public function build()
    {
        $configuration = parent::build();
        return $this->composer->configure($configuration);
    }

    /**
     * Will create a new builder for a ColumnConfigurationBuilder.
     *
     * @param DataComposer $composer
     * @return ColumnBuilder
     */
    public static function create(DataComposer $composer)
    {
        return new ColumnBuilder($composer);
    }


}