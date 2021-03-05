<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Guides\Renderers\LaTeX;

use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\Nodes\MainNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Renderer;
use phpDocumentor\Guides\Renderers\DocumentNodeRenderer as BaseDocumentRender;
use phpDocumentor\Guides\Renderers\FullDocumentNodeRenderer;
use phpDocumentor\Guides\Renderers\NodeRenderer;
use function count;

class DocumentNodeRenderer implements NodeRenderer, FullDocumentNodeRenderer
{
    /** @var Renderer */
    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render(Node $node) : string
    {
        if ($node instanceof DocumentNode === false) {
            throw new \InvalidArgumentException('Invalid node presented');
        }

        return (new BaseDocumentRender())->render($node);
    }

    public function renderDocument(DocumentNode $node) : string
    {
        return $this->renderer->render(
            'document.tex.twig',
            [
                'isMain' => $this->isMain($node),
                'document' => $node,
                'body' => $this->render($node),
            ]
        );
    }

    private function isMain(DocumentNode $node) : bool
    {
        $nodes = $node->getNodes(
            static function ($node) {
                return $node instanceof MainNode;
            }
        );

        return count($nodes) !== 0;
    }
}
