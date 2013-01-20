<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
class syntax_plugin_chem extends DokuWiki_Syntax_Plugin {
  function getType(){ return 'formatting'; }
  function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
  function getSort(){ return 158; }
  function connectTo($mode) { $this->Lexer->addEntryPattern('<chem>(?=.*?</chem>)',$mode,'plugin_chem'); }
  function postConnect() { $this->Lexer->addExitPattern('</chem>','plugin_chem'); }

  function handle($match, $state, $pos, &$handler){
    switch ($state) {
        case DOKU_LEXER_ENTER     :return array($state, '');
        case DOKU_LEXER_UNMATCHED :return array($state, $match);
        case DOKU_LEXER_EXIT      :return array($state, '');
    }
    return array();
  }

  function render($mode, &$renderer, $data) {
    if($mode == 'xhtml' || $mode=='odt'){
      list($state, $match) = $data;
      switch ($state) {
        case DOKU_LEXER_ENTER:break;
        case DOKU_LEXER_UNMATCHED:
          if($mode=='xhtml'){
            // xhtml
            $renderer->doc .= $this->getChemFormat($match);
          }else{
            // Open document format
            $renderer->doc.= $this->getOdtChemFormat($match);
          }
          break;
        case DOKU_LEXER_EXIT:break;
      }
      return true;
    }
    return false;
  }

  function getChemFormat($raw){
    $pattern = array("/([A-Za-z\]\)]+)(0)/", "/[\|]?([0-9]*)[\^]/", "/([^ ][\]\)]?)[\|]?(([\-\+][0-9]*)|([0-9]*[\-\+]))/", "/([A-Z]|[a-z]|\)|\])([1-9][0-9]*)/");
    $replace = array("\${1}<sup>\${2}</sup>", "<sup>\${1}</sup>", "\${1}<sup>\${2}</sup>","\${1}<sub>\${2}</sub>");
    return preg_replace($pattern,$replace,$raw);
  }
  function getOdtChemFormat($raw){
    $c = $this->getChemFormat($raw);
    $pattern = array("/<sup>([^<]+)<\/sup>/","/<sub>([^<]+)<\/sub>/");
    $replace = array("<text:span text:style-name=\"sup\">\${1}</text:span>",
                       "<text:span text:style-name=\"sub\">\${1}</text:span>");
    return preg_replace($pattern,$replace,$c);
  }
 }
?>
