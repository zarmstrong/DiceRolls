v2.1.2
- FIX Faulty regex returning null instead of the actual message.
- FIX Rolls' TAB not showing up on regular user accounts upon installation.
-
- The following to prevent cheats:
- ENHANCEMENT - User should not have permission to delete or modify existing rolls as default.
-
- The followings to keep consistency with the core code:
- ENHANCEMENT - Posts containing rolls can be safely soft-deleted and restored.
- ENHANCEMENT - Deleting rolls doesn't affect quoted ones.
- ENHANCEMENT - Editing rolls doesn't affect quoted ones.
- ENHANCEMENT - Deleting forum content doesn't affect rolls quoted elsewhere, ie.: other forums.
- ENHANCEMENT - Deleting topics doesn't affect rolls quoted elsewhere, ie.: other topics.
-
- Code housekeeping

v2.1.1
- Hardened user input about skins directory
- Provided related meaningful error report
- Code housekeeping

v2.1.0
- Change skins' dir to {phpbb_root}images/dice/
- Add migration to mirror the skins in the new images path
- Remove a configuration (dice version)
- Added Spanish formal
- Code housekeeping

v2.0.1
- FIX Quoted rolls aren't always rendered.
- Adjust migration
- Code housekeeping

v2.0.0-beta
- first public release
