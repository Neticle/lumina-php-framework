<?php

namespace system\web\authentication\oauth\server\data;

interface ISession {
	
	/**
	 * Returns the currently authenticated end-user, if any.
	 *
	 * @return IResourceOwner
	 *  The resource owner (end-user), if any, or NULL otherwise.
	 */
	public function getEndUser ();
	
}
