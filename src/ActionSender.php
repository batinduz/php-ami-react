<?php

namespace Clue\React\Ami;

use Clue\React\Ami\Client;
use Clue\React\Ami\Protocol\Response;
use Clue\React\Ami\Protocol\Action;
use UnexpectedValueException;
use Clue\React\Ami\Protocol\Event;

class ActionSender
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


   //custom functions 
   
    public function setVar($channel, $variable, $value)
    {
        return $this->request('Setvar', ['Channel' => $channel, 'Variable' => $variable, 'Value' => $value]);
    }
 public function originate($channel,
                       $exten=NULL, $context=NULL, $priority=NULL,
                       $application=NULL, $data=NULL,
                       $timeout=NULL, $callerid=NULL, $variable=NULL, $account=NULL, $async=NULL, $actionid=NULL)
    {
      $parameters = array('Channel'=>$channel);
      if($exten) $parameters['Exten'] = $exten;
      if($context) $parameters['Context'] = $context;
      if($priority) $parameters['Priority'] = $priority;
      if($application) $parameters['Application'] = $application;
      if($data) $parameters['Data'] = $data;
      if($timeout) $parameters['Timeout'] = $timeout;
      if($callerid) $parameters['CallerID'] = $callerid;
      if($variable) $parameters['Variable'] = $variable;
      if($account) $parameters['Account'] = $account;
      if(!is_null($async)) $parameters['Async'] = ($async) ? 'true' : 'false';
      if($actionid) $parameters['ActionID'] = $actionid;
      return $this->request('Originate', $parameters);
    }

  public function timeout($channel, $cause)
    {
        return $this->request('AbsoluteTimeout', array('Channel' => $channel, 'Timeout' => $cause));
    }
    public function getvar($channel, $cause)
    {
    return $this->request('GetVar', array('Channel' => $channel, 'Variable' => $cause));
    }



    public function login($username, $secret, $events = null)
    {
        $events = $this->boolParam($events);
        return $this->request('Login', array('UserName' => $username, 'Secret' => $secret, 'Events' => $events));
    }

    public function logoff()
    {
        return $this->request('Logoff');
    }

    public function agentLogoff($agentId, $soft = false)
    {
        $bool = $soft ? 'true' : 'false';
        return $this->request('AgentLogoff', array('Agent' => $agentId, 'Soft' => $bool));
    }

    public function ping()
    {
        return $this->request('Ping');
    }

    public function coreShowChannels()
    {
        return $this->request('CoreShowChannels');
    }

    public function command($command)
    {
        return $this->request('Command', array('Command' => $command));
    }

    public function events($eventMask)
    {
        if ($eventMask === false) {
            $eventMask = 'off';
        } elseif ($eventMask === true) {
            $eventMask = 'on';
        } else {
            $eventMask = implode(',', $eventMask);
        }

        return $this->request('Events', array('EventMask' => $eventMask));
    }

    public function sipPeers()
    {
        return $this->request('SIPPeers');
    }

    public function sipShowPeer($peerName)
    {
        return $this->request('SIPshowpeer', array('Peer' => $peerName));
    }

    public function listCommands()
    {
        return $this->request('ListCommands');
    }

    public function sendText($channel, $message)
    {
        return $this->request('Sendtext', array('Channel' => $channel, 'Message' => $message));
    }

    public function hangup($channel, $cause)
    {
        return $this->request('Hangup', array('Channel' => $channel, 'Cause' => $cause));
    }

    public function challenge($authType = 'MD5')
    {
        return $this->request('Challenge', array('AuthType' => $authType));
    }

    public function getConfig($filename, $category = null)
    {
        return $this->request('GetConfig', array('Filename' => $filename, 'Category' => $category));
    }

    private function boolParam($value)
    {
        if ($value === true) {
            return 'on';
        }
        if ($value === false) {
            return 'off';
        }
        return null;
    }

    private function request($name, array $args = array())
    {
        return $this->client->request($this->client->createAction($name, $args));
    }
}
