<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Pblbanners_UsersModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_PblUsers';

	public function updateEntry($id, Array $data) {
		if (isset($data['add']) && $data['add']) {
			$data['deposit'] = $data['deposit'] + $data['add'];
		}

		$entry = $this->getEntry($id);

		$res = parent::updateEntry($id, $data);

		if ($res) {
			if (isset($data['active']) && $data['active'] == 1 && $entry['active'] == 0) {
				$this->_DP("Email_PagesTemplate")->
					sendChangeStatusNotification($entry, 110, "advert@expopromoter.com",
						"ExpoAdvert Advertisement Network", "advert@expopromoter.com");
			} else if (isset($data['active']) && $data['active'] == 0 && $entry['active'] == 1) {
				$this->_DP("Email_PagesTemplate")->
					sendChangeStatusNotification($entry, 127, "advert@expopromoter.com",
						"ExpoAdvert Advertisement Network", "advert@expopromoter.com");
			}
		}

		//Если значение депозита изменилось, заносим информацию о зачислении денег на депозит в лог
		if ($res && isset($data['deposit']) && $data['deposit']!=$entry['deposit']) {
			$deposit_delta = ($data['deposit'] - $entry['deposit']);
			$this->_DP("List_Banners_PblStatUsersCharges")->insertEntry(
				array(
					'users_id' => $id,
					'value' => $deposit_delta
				)
			);
			
			//Дополнительно сохраняем в таблицу transactions
			$this->_DP("List_Transactions")->insertEntry(
				array(
					'source' => 'admin',
					'type' => 'deposit',
					'id_advertiser' => $id,
					'summ' => $deposit_delta
				)
			);
		}

		if ($res) {
			//Обновляем материализованное представление полностью
			$this->_DP("List_Banners_PblBanners")->updateMView();
		}

		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			//Обновляем материализованное представление полностью
			$this->_DP("List_Banners_PblBanners")->updateMView();
		}

		return $res;
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, false);

		//Сохранение в историю зачислений начального депозита пользователя при добавлении
		if ($res && isset($data['deposit']) && intval($data['deposit']) != 0) {
			$id = $this->_DP_obj->getLastInsertId();
			$this->_DP("List_Banners_PblStatUsersCharges")->insertEntry(array('users_id' => $id, 'value' => ($data['deposit'])));
		}

		return $res;
	}

}