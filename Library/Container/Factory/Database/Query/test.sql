SELECT config_group.groupId,
       config_group.groupName,
       config_group.groupPre,
       config_group.groupAfter,
       config_group.groupClass,
       config_group.groupLogin,
       dataVariableCreated,
       dataVariableEdited
FROM psiportal_config_group AS config_group
       JOIN psiportal_config_group_rights AS config_group_rights ON config_group_rights.groupId = config_group.groupId
