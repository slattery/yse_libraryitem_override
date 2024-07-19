# YSE LibraryItem Override

## Override stored behavior with local behavior in a from_library oaragraph.

if the entity passed in is a 'from_library' para, then we check for a 'library_override' we check for the plugin, and if that library item was toggled for override then we swap in the outer from_library settings and remove the stored para settings we need to reset the view mode to make sure that only the fields needed, and the twig suggestions are correct. The hook_entity_view_mode_alter stage seems to work at the right time, so that when the LibraryItem entity does its complete object swap to present its stored para as if it were the first-level object, the changes have been made and not defeated by the library_item hooks.

The reason for the separate toggle is that often we do want the stored behaviors, and we don't what to rely on each plugin's controls to have a 'null choice' option as the default. So we need to opt in.
