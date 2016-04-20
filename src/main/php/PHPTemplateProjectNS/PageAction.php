<?php

abstract class PHPTemplateProjectNS_PageAction extends PHPTemplateProjectNS_Component implements TOGoS_Action
{
	protected function templateResponse( $statusCode=200, $viewName, $vars=array(), $typeOrHeaders='text/html' ) {
		$pageUtil = new PHPTemplateProjectNS_PageUtil($this->registry, $vars);
		$blob = $pageUtil->viewBlob($viewName);
		return Nife_Util::httpResponse( $statusCode, $blob, $typeOrHeaders );
	}
	
	protected function jsonResponse($statusCode, $thing) {
		return Nife_Util::httpResponse( $statusCode, new EarthIT_JSON_PrettyPrintedJSONBlob($thing), "application/json" );
	}
	
	protected function redirect( $statusCode, $location ) {
		return Nife_Util::httpResponse($statusCode, "Redirecting to $location...", ['location'=>$location]);
	}
	
	protected function getErrorMessage( $messageId, PHPTemplateProjectNS_ActionContext $actx ) {
		$message = $actx->getSessionVariable('errorMessage');
		return hash('sha1',$message) == $messageId ? $message : null;
	}
	
	protected function redirectWithErrorMessage( $location, $message, PHPTemplateProjectNS_ActionContext $actx ) {
		if( is_array($message) ) $message = implode("\n", $message);
		$actx->setSessionVariable('errorMessage', $message);
		$messageHash = hash('sha1', $message);
		return $this->redirect(303, "{$location}?error-message-id={$messageHash}");
	}
	
	public function isAllowed( PHPTemplateProjectNS_ActionContext $actx, &$status, array &$notes=[] ) {
		return true;
	}
	
	/**
	 * Given a path relative to '/' (including the '/'), make a
	 * relative URL from the current path (in $actx) to that location.
	 */
	protected function relativePath($to, PHPTemplateProjectNS_ActionContext $actx) {
		// TODO: Fix when $actx->getPath() works
		return substr($to,1);
	}
	
	protected function blobUrl($ref, $filenameHint='?', PHPTemplateProjectNS_ActionContext $actx) {
		list($urn,$blobId) = PHPTemplateProjectNS_BlobIDUtil::parseRef($ref);
		$filename = str_replace('?',substr($blobId,0,12),$filenameHint);
		return $this->relativePath("/uri-res/raw/$urn/$filename", $actx);
	}
	
	/** Return a Nife_HTTP_Response */
	public abstract function __invoke( PHPTemplateProjectNS_ActionContext $actx );
}
