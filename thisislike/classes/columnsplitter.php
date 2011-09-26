<?php
/**
 * @file    columnsplitter.php
 *
 * htmldomsplitter class
 *
 *
 * copyright (c) 2011 Frank Hellenkamp [jonas@depagecms.net]
 *
 * @author    Frank Hellenkamp [jonas@depagecms.net]
 */

class columnsplitter {
    // {{{ variables
    protected $dimensions = array();
    protected $maxheight = 200;
    protected $columns = array();
    protected $activeColumn;
    protected $insertInfo = false;
    // }}}

    // {{{ setFontDimensions()
    public function setFontDimensions($dimensions) {
        $this->dimensions = $dimensions;
    }
    // }}}
    // {{{ setMaxheight()
    public function setMaxheight($maxheight) {
        $this->maxheight = $maxheight;
    }
    // }}}
    
    // {{{ split()
    public function split($dom) {
        $this->columns = array();
        $this->addColumn();

        $columnheight = 0;

        // count chars
        $nodes = $dom->getBodyNodes();
        foreach ($nodes as $node) {
            $nodename = strtolower($node->nodeName);
            // get dimensions for current node
            $dim = $this->getFontDimensionsFor($nodename);

            // loop through children
            $height = 0;
            $nodeToAdd = $this->activeColumn->importNode($node, false);

            for ($i = 0; $i < $node->childNodes->length; $i++) {
                // add childnodes
                $child = $node->childNodes->item($i);

                // get statistical height of child-element
                $height = $this->getHeightFor($child, $dim);

                if ($columnheight + $height > $this->maxheight) {
                    // node does not fit completely into column - so split it
                    if ($nodename == "p" && $child->nodeType == XML_TEXT_NODE) {
                        $restheight = $this->maxheight - $columnheight;
                        $startpos = ceil($restheight / $dim['lineHeight']) * $dim['charsPerLine'] - 10;

                        if ($startpos > 0 && $startpos < $child->length && $startpos > $dim['charsPerLine'] && $restheight > 2 * $dim['lineHeight']) {
                            $splitpos = strpos($child->data, " ",  $startpos);

                            // split text
                            $text1 = substr($child->data, 0, $splitpos);
                            $text2 = substr($child->data, $splitpos + 1);

                            // first text-node
                            $textNode = new \DOMText($text1);
                            $nodeToAdd->appendChild($this->activeColumn->importNode($textNode));

                            $this->addColumn($nodeToAdd, "split: " . $columnheight);
                            $nodeToAdd = $this->activeColumn->importNode($node, false);

                            // second text-node
                            $textNode = new \DOMText($text2);
                            $nodeToAdd->appendChild($this->activeColumn->importNode($textNode));

                            $columnheight = $this->getHeightFor($textNode, $dim);
                            $height = $columnheight;
                        } else {
                            // copy whole child to next column
                            $this->addColumn($nodeToAdd, "whole child: {$columnheight} - {$height}");
                            $nodeToAdd = $this->activeColumn->importNode($node, false);
                            $nodeToAdd->appendChild($this->activeColumn->importNode($child, true));

                            $columnheight = $height;
                        }
                    } else {
                        // copy whole node to next column
                        $this->addColumn($nodeToAdd, "whole node: {$columnheight} - {$height}");
                        $nodeToAdd = $this->activeColumn->importNode($node, false);
                        $nodeToAdd->appendChild($this->activeColumn->importNode($child, true));

                        $columnheight = $height;
                    }
                } else {
                    // copy child to active column
                    $copy = $this->activeColumn->importNode($child, true);
                    $nodeToAdd->appendChild($copy);
                    $columnheight += $height;
                }
            }

            $this->addInfoNode("copy {$nodename}: {$columnheight} - {$height}");

            // copy whole node to activeColumn
            $copy = $this->activeColumn->importNode($nodeToAdd, true);
            $this->activeBody->appendChild($copy);
        }

        return $this->columns;
    }
    // }}}
    
    // {{{ addColumn()
    protected function addColumn($nodeForOldColumn = null, $info = "") {
        if ($nodeForOldColumn != null) {
            $copy = $this->activeColumn->importNode($nodeForOldColumn, true);
            $this->activeBody->appendChild($copy);
        }

        $this->columns[] = new htmldom();
        $this->activeColumn = $this->columns[count($this->columns) - 1];
        $this->activeBody = $this->activeColumn->getBodyNode();

        $this->addInfoNode($info, "absolute");
    }
    // }}}
    // {{{ addInfoNode()
    protected function addInfoNode($info = "", $class = "") {
        if ($this->insertInfo && $info != "") {
            $infoNode = $this->activeColumn->createElement("span", $info);
            $infoNode->setAttribute("class", "info $class");
            $this->activeBody->appendChild($infoNode);
        }
    }
    // }}}
    
    // {{{ getFontDimensionsFor()
    protected function getFontDimensionsFor($nodename) {
        $nodename = strtolower($nodename);

        if (isset($this->dimensions[$nodename])) {
            return $this->dimensions[$nodename];
        } else {
            // return paragraph as default
            return $this->dimensions['p'];
        }
    }
    // }}}
    // {{{ getHeightFor()
    protected function getHeightFor($node, $dim) {
        $height = 0;

        if ($node->nodeType == XML_TEXT_NODE) {
            // text nodes
            $len = $node->length;
        } elseif ($node->nodeName == "img" || $node->nodeName == "object") {
            // image
            $height = (int) $node->getAttribute("height");

            return $height;
        } else if ($node->nodeName == "ul" || $node->nodeName == "ol") {
            // lists
            foreach ($node->childNodes as $child) {
                $height += $this->getHeightFor($child, $dim);
            }

            return $height;
        } else {
            // other content
            $textcontent = strip_tags($node->ownerDocument->saveHTML($node));
            $len = strlen($textcontent);

            // add heights of included images
            $xpath = new \DOMXPath($node->ownerDocument);
            $nodelist = $xpath->query(".//*[(name() = 'img' or name() = 'object') and @height]", $node);

            foreach ($nodelist as $n) {
                $height += $n->getAttribute("height");
            }
        }

        $lines = ceil($len / $dim['charsPerLine']);
        $height += $lines * $dim['lineHeight'] + $dim['padding'];

        return $height;
    }
    // }}}
}

/* vim:set ft=php fenc=UTF-8 sw=4 sts=4 fdm=marker et : */
