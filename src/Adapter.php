<?php

namespace studioespresso\mailersend;

use Craft;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use craft\mail\transportadapters\BaseTransportAdapter;
use Symfony\Component\Mailer\Bridge\MailerSend\Transport\MailerSendApiTransport;

/**
 * Adapter represents the MailerSend mail adapter.
 *
 * @author Studio Espresso <support@studioespresso.co>
 * @since 1.0
 */
class Adapter extends BaseTransportAdapter
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'MailerSend';
    }

    /**
     * @var string
     */
    public ?string $token = null;

    /**
     * @var string|null
     */
    public ?string $messageStream = null;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Craft::t('mailersend', 'Token'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'token',
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return [
            [['token'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('mailersend/settings', [
            'adapter' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function defineTransport(): array|\Symfony\Component\Mailer\Transport\AbstractTransport
    {
        $transport = new MailerSendApiTransport(App::parseEnv($this->token));
        return $transport;
    }
}
