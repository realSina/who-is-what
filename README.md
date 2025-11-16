# Who is What
A fun and interactive Telegram bot built with the MadelineProto library in PHP. It randomly tags people in your Telegram group chats and assigns funny roles or attributes to them.

## About
**Who is What** is a playful Telegram bot designed to spice up conversations in Persian-speaking supergroups. The bot randomly tags members of your group and assigns them humorous or quirky roles based on your questions. Simply add the bot as an administrator to your supergroup, and you're ready to start having fun!

### How it Works
1. **Start the bot**: Launch the bot and add it as an administrator in your Telegram supergroup.
2. **Ask a question**: To get the bot to tag someone and assign a role or attribute, ask a question using Persian phrases like `کسی` (who) or `چه کسی` (who is) at the beginning of your sentence.
   
   **Example**: 
   - `چه کسی امروز خوشحاله؟` (Who is happy today?)
   - `کی عاشق چای هست؟` (Who loves tea?)

The bot will randomly pick a person from the group and assign them the attribute or role you're asking about. It’s a great way to create fun interactions and bring some laughter to your chats!

### Features:
- **Random tagging**: Randomly selects people in the group and assigns them a humorous role.
- **Persian language support**: Designed specifically for Persian-speaking groups, with phrases tailored to Persian culture and humor.
- **Easy integration**: Just add the bot as an admin and start asking your questions.
- **MadelineProto**: Built using the powerful MadelineProto library for seamless interaction with the Telegram API.

### Example Usage:
- **User**: `چه کسی امروز خوشحاله؟`
- **Bot**:  @random_user "احتمالا امروز خوشحاله!"

- **User**: `کی از همه خوشگل تره؟`
- **Bot**: @random_user "به نظرم از همه خوشگل تره!"

### Installation:
1. Clone this repository or download the source code.
2. Set up the MadelineProto library in your project by following the installation instructions [here](https://github.com/danog/MadelineProto).
3. Create a new bot on Telegram via [BotFather](https://core.telegram.org/bots#botfather) and obtain your bot token.
4. Add the bot to your Telegram supergroup as an administrator.
5. Configure the bot by editing the settings in the PHP script (e.g., setting your bot token).
6. Run the bot and start asking fun questions in your group!

### License:
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

### Contributing:
Feel free to fork the repository, make changes, and submit pull requests. If you have suggestions or find bugs, don't hesitate to open an issue!

### Note:
- This bot is for fun and entertainment in Persian-language Telegram supergroups.
- Make sure to use it in groups where people enjoy playful interactions!
