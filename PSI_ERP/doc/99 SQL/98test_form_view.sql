TRUNCATE TABLE `t_fv_md`;
INSERT INTO `t_fv_md` (id, parent_id, prop_name, prop_value, show_order) VALUES
('01', NULL, 'view_name', '销售订单', 1),
('0101', '01', 'fid', '2028', 0),
('0102', '01', 'title', '销售订单', 0),
('0103', '01', 'view_type', '2', 0),
('0104', '01', 'tool_bar_id', '010401', 0),
('01040101', '010401', 'button_text', '新建销售订单', 1),
('0104010101', '01040101', 'button_handler', 'onTodo', 0),
('01040102', '010401', 'button_text', '-', 2),
('01040103', '010401', 'button_text', '编辑销售订单', 3),
('0104010301', '01040103', 'button_handler', 'onTodo', 0),
('01040104', '010401', 'button_text', '-', 4),
('01040105', '010401', 'button_text', '删除销售订单', 5),
('0104010501', '01040105', 'button_handler', 'onTodo', 0),
('01040106', '010401', 'button_text', '-', 6),
('01040107', '010401', 'button_text', '打印', 7),
('0104010701', '01040107', 'sub_button_id', '010401070101', 0),
('01040107010101', '010401070101', 'button_text', '打印预览', 1),
('01040107010102', '01040107010101', 'button_icon', 'PSI-button-print-preview', 0),
('0104010701010101', '01040107010101', 'button_handler', 'onTodo', 0),
('01040107010103', '010401070101', 'button_text', '-', 2),
('01040107010104', '010401070101', 'button_text', '直接打印', 3),
('01040107010105', '01040107010104', 'button_icon', 'PSI-button-print', 0),
('0104010701010301', '01040107010104', 'button_handler', 'onTodo', 0),
('01040108', '010401', 'button_text', '-', 8),
('01040109', '010401', 'button_text', '帮助', 9),
('0104010901', '01040109', 'button_handler', 'onHelp', 0),
('0104010902', '01040109', 'button_icon', 'PSI-help', 0),
('01040110', '010401', 'button_text', '-', 10),
('01040111', '010401', 'button_text', '关闭', 11),
('0104011101', '01040111', 'button_handler', 'onCloseForm', 0),
('0105', '01', 'help_id', 'sobill', 0),
('0106', '01', 'query_panel_col_count', '4', 0),
('0107', '01', 'query_panel_id', '010701', 0),
('01070101', '010701', 'query_cmp_label', '状态', 1),
('0107010101', '01070101', 'query_cmp_xtype', 'textfield', 0),
('01070102', '010701', 'query_cmp_label', '单号', 2),
('0107010201', '01070102', 'query_cmp_xtype', 'textfield', 0),
('02', NULL, 'view_name', '仓库', 2),
('0201', '02', 'fid', '1003', 0),
('0202', '02', 'title', '仓库', 0),
('0203', '02', 'view_type', '1', 0),
('0204', '02', 'tool_bar_id', '020401', 0),
('02040101', '020401', 'button_text', '新增仓库', 1),
('0204010101', '02040101', 'button_handler', 'onTodo', 0),
('02040102', '020401', 'button_text', '-', 2),
('02040103', '020401', 'button_text', '编辑仓库', 3),
('0204010301', '02040103', 'button_handler', 'onTodo', 0),
('02040104', '020401', 'button_text', '-', 4),
('02040105', '020401', 'button_text', '删除仓库', 5),
('0204010501', '02040105', 'button_handler', 'onTodo', 0),
('02040106', '020401', 'button_text', '-', 6),
('02040107', '020401', 'button_text', '帮助', 7),
('0204010701', '02040107', 'button_handler', 'onHelp', 0),
('02040108', '020401', 'button_text', '-', 8),
('02040109', '020401', 'button_text', '关闭', 9),
('0204010901', '02040109', 'button_handler', 'onCloseForm', 0),
('0205', '02', 'help_id', 'warehouse', 0);
